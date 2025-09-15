<?php
session_start();
ini_set('display_errors', 1);

class Auth {
    private $db;

    public function __construct() {
        ob_start();
        include '../config/db_connect.php';
        $this->db = $conn;
    }

    function __destruct() {
        $this->db->close();
        ob_end_flush();
    }

    // Register new user
    function register() {
        extract($_POST);
        
        // Validate input
        if (empty($email) || empty($password) || empty($role) || empty($full_name)) {
            return ['status' => 'error', 'message' => 'All fields are required'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email format'];
        }

        if (strlen($password) < 6) {
            return ['status' => 'error', 'message' => 'Password must be at least 6 characters'];
        }

        if (!in_array($role, ['student', 'owner'])) {
            return ['status' => 'error', 'message' => 'Invalid role'];
        }

        // Check if email already exists
        $check = $this->db->query("SELECT id FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            return ['status' => 'error', 'message' => 'Email already registered'];
        }

        // Hash password
        $password_hash = md5($password);
        
        // Insert user
        $user_data = "email = '$email'";
        $user_data .= ", password_hash = '$password_hash'";
        $user_data .= ", role = '$role'";
        $user_data .= ", status = 'pending'";
        $user_data .= ", email_verified = 0";
        
        if (!empty($phone)) {
            $user_data .= ", phone = '$phone'";
        }

        $save_user = $this->db->query("INSERT INTO users SET $user_data");
        
        if (!$save_user) {
            return ['status' => 'error', 'message' => 'Failed to create user account'];
        }

        $user_id = $this->db->insert_id;

        // Create profile based on role
        if ($role == 'student') {
            $profile_data = "user_id = $user_id";
            $profile_data .= ", full_name = '$full_name'";
            $profile_data .= ", gender = '$gender'";
            $profile_data .= ", university = '$university'";
            
            if (!empty($date_of_birth)) {
                $profile_data .= ", date_of_birth = '$date_of_birth'";
            }
            
            if (!empty($emergency_contact)) {
                $profile_data .= ", emergency_contact = '$emergency_contact'";
            }

            $save_profile = $this->db->query("INSERT INTO student_profiles SET $profile_data");
        } else if ($role == 'owner') {
            $profile_data = "user_id = $user_id";
            $profile_data .= ", full_name = '$full_name'";
            $profile_data .= ", verified = 0";
            
            if (!empty($business_name)) {
                $profile_data .= ", business_name = '$business_name'";
            }

            $save_profile = $this->db->query("INSERT INTO owner_profiles SET $profile_data");
        }

        if (!$save_profile) {
            // Rollback user creation if profile creation fails
            $this->db->query("DELETE FROM users WHERE id = $user_id");
            return ['status' => 'error', 'message' => 'Failed to create profile'];
        }

        // Log registration
        $this->log_audit($user_id, 'register', 'user', $user_id, ['role' => $role]);

        return ['status' => 'success', 'message' => 'Registration successful. Please verify your email to activate your account.'];
    }

    // Login user
    function login() {
        extract($_POST);
        
        if (empty($email) || empty($password)) {
            return ['status' => 'error', 'message' => 'Email and password are required'];
        }

        $password_hash = md5($password);
        $qry = $this->db->query("SELECT * FROM users WHERE email = '$email' AND password_hash = '$password_hash'");
        
        if ($qry->num_rows > 0) {
            $user = $qry->fetch_assoc();
            
            // Check if account is active
            if ($user['status'] != 'active') {
                return ['status' => 'error', 'message' => 'Account is not active. Please verify your email or contact support.'];
            }

            // Set session variables
            $_SESSION['login_id'] = $user['id'];
            $_SESSION['login_email'] = $user['email'];
            $_SESSION['login_role'] = $user['role'];
            $_SESSION['login_status'] = $user['status'];

            // Get profile information
            if ($user['role'] == 'student') {
                $profile = $this->db->query("SELECT * FROM student_profiles WHERE user_id = {$user['id']}");
                if ($profile->num_rows > 0) {
                    $profile_data = $profile->fetch_assoc();
                    $_SESSION['login_name'] = $profile_data['full_name'];
                    $_SESSION['login_gender'] = $profile_data['gender'];
                    $_SESSION['login_university'] = $profile_data['university'];
                }
            } else if ($user['role'] == 'owner') {
                $profile = $this->db->query("SELECT * FROM owner_profiles WHERE user_id = {$user['id']}");
                if ($profile->num_rows > 0) {
                    $profile_data = $profile->fetch_assoc();
                    $_SESSION['login_name'] = $profile_data['full_name'];
                    $_SESSION['login_verified'] = $profile_data['verified'];
                }
            } else if ($user['role'] == 'admin') {
                $profile = $this->db->query("SELECT * FROM admin_profiles WHERE user_id = {$user['id']}");
                if ($profile->num_rows > 0) {
                    $profile_data = $profile->fetch_assoc();
                    $_SESSION['login_name'] = $profile_data['full_name'];
                }
            }

            // Log login
            $this->log_audit($user['id'], 'login', 'user', $user['id']);

            return ['status' => 'success', 'message' => 'Login successful'];
        } else {
            return ['status' => 'error', 'message' => 'Invalid email or password'];
        }
    }

    // Logout user
    function logout() {
        if (isset($_SESSION['login_id'])) {
            $this->log_audit($_SESSION['login_id'], 'logout', 'user', $_SESSION['login_id']);
        }
        
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        
        // Check if this is an AJAX request or direct link
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // AJAX request - return JSON
            return ['status' => 'success', 'message' => 'Logged out successfully'];
        } else {
            // Direct link - redirect to login page
            header('Location: ../pages/login.php');
            exit();
        }
    }

    // Check if user has permission
    function has_permission($action, $resource = null) {
        if (!isset($_SESSION['login_id'])) {
            return false;
        }

        $role = $_SESSION['login_role'];
        
        // Admin has all permissions
        if ($role == 'admin') {
            return true;
        }

        // Define role-based permissions
        $permissions = [
            'student' => [
                'view_listings', 'search_listings', 'create_booking', 'view_own_bookings',
                'send_message', 'create_review', 'create_complaint', 'view_own_profile'
            ],
            'owner' => [
                'create_listing', 'edit_own_listing', 'view_own_listings', 'manage_bookings',
                'send_message', 'view_own_profile', 'respond_to_reviews'
            ]
        ];

        return in_array($action, $permissions[$role] ?? []);
    }

    // Get user profile
    function get_profile($user_id = null) {
        if (!$user_id) {
            $user_id = $_SESSION['login_id'] ?? null;
        }

        if (!$user_id) {
            return null;
        }

        $user = $this->db->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
        
        if ($user['role'] == 'student') {
            $profile = $this->db->query("SELECT * FROM student_profiles WHERE user_id = $user_id")->fetch_assoc();
        } else if ($user['role'] == 'owner') {
            $profile = $this->db->query("SELECT * FROM owner_profiles WHERE user_id = $user_id")->fetch_assoc();
        } else if ($user['role'] == 'admin') {
            $profile = $this->db->query("SELECT * FROM admin_profiles WHERE user_id = $user_id")->fetch_assoc();
        }

        return array_merge($user, $profile ?? []);
    }

    // Update user profile
    function update_profile() {
        extract($_POST);
        
        if (!isset($_SESSION['login_id'])) {
            return ['status' => 'error', 'message' => 'Not authenticated'];
        }

        $user_id = $_SESSION['login_id'];
        $role = $_SESSION['login_role'];

        // Update user basic info
        $user_data = [];
        if (!empty($email)) {
            $user_data[] = "email = '$email'";
        }
        if (!empty($phone)) {
            $user_data[] = "phone = '$phone'";
        }
        if (!empty($password)) {
            $user_data[] = "password_hash = '" . md5($password) . "'";
        }

        if (!empty($user_data)) {
            $this->db->query("UPDATE users SET " . implode(', ', $user_data) . " WHERE id = $user_id");
        }

        // Update profile based on role
        if ($role == 'student') {
            $profile_data = [];
            if (!empty($full_name)) {
                $profile_data[] = "full_name = '$full_name'";
            }
            if (!empty($gender)) {
                $profile_data[] = "gender = '$gender'";
            }
            if (!empty($university)) {
                $profile_data[] = "university = '$university'";
            }
            if (!empty($date_of_birth)) {
                $profile_data[] = "date_of_birth = '$date_of_birth'";
            }
            if (!empty($emergency_contact)) {
                $profile_data[] = "emergency_contact = '$emergency_contact'";
            }

            if (!empty($profile_data)) {
                $this->db->query("UPDATE student_profiles SET " . implode(', ', $profile_data) . " WHERE user_id = $user_id");
            }
        } else if ($role == 'owner') {
            $profile_data = [];
            if (!empty($full_name)) {
                $profile_data[] = "full_name = '$full_name'";
            }
            if (!empty($business_name)) {
                $profile_data[] = "business_name = '$business_name'";
            }

            if (!empty($profile_data)) {
                $this->db->query("UPDATE owner_profiles SET " . implode(', ', $profile_data) . " WHERE user_id = $user_id");
            }
        }

        $this->log_audit($user_id, 'update_profile', 'user', $user_id);

        return ['status' => 'success', 'message' => 'Profile updated successfully'];
    }

    // Log audit trail
    function log_audit($actor_user_id, $action, $entity, $entity_id, $meta = null) {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $meta_json = $meta ? json_encode($meta) : null;
        
        $this->db->query("INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta, ip_address, user_agent) 
                         VALUES ($actor_user_id, '$action', '$entity', $entity_id, '$meta_json', '$ip_address', '$user_agent')");
    }

    // Get audit logs
    function get_audit_logs($limit = 100, $offset = 0) {
        $qry = $this->db->query("SELECT al.*, u.email as actor_email, u.role as actor_role 
                                FROM audit_logs al 
                                LEFT JOIN users u ON al.actor_user_id = u.id 
                                ORDER BY al.created_at DESC 
                                LIMIT $limit OFFSET $offset");
        
        $logs = [];
        while ($row = $qry->fetch_assoc()) {
            $logs[] = $row;
        }
        
        return $logs;
    }

    // Verify email (placeholder - would need email service)
    function verify_email($token) {
        // This would typically involve checking a verification token
        // For now, we'll just mark email as verified
        if (isset($_SESSION['login_id'])) {
            $this->db->query("UPDATE users SET email_verified = 1 WHERE id = {$_SESSION['login_id']}");
            return ['status' => 'success', 'message' => 'Email verified successfully'];
        }
        
        return ['status' => 'error', 'message' => 'Invalid verification token'];
    }

    // Reset password (placeholder)
    function reset_password() {
        extract($_POST);
        
        if (empty($email)) {
            return ['status' => 'error', 'message' => 'Email is required'];
        }

        $user = $this->db->query("SELECT id FROM users WHERE email = '$email'");
        
        if ($user->num_rows > 0) {
            // In a real implementation, you would:
            // 1. Generate a secure reset token
            // 2. Send email with reset link
            // 3. Store token with expiration
            
            return ['status' => 'success', 'message' => 'Password reset instructions sent to your email'];
        } else {
            return ['status' => 'error', 'message' => 'Email not found'];
        }
    }
}
?>
