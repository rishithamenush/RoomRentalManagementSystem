<?php
ob_start();
$action = $_GET['action'];

// Use new auth system for authentication actions
if(in_array($action, ['login', 'register', 'logout', 'reset_password', 'verify_email'])){
    include '../classes/auth_class.php';
    $auth = new Auth();
    
    if($action == 'login'){
        $result = $auth->login();
        echo json_encode($result);
    }
    if($action == 'register'){
        $result = $auth->register();
        echo json_encode($result);
    }
    if($action == 'logout'){
        $result = $auth->logout();
        // If it's a direct request (not AJAX), the logout function will redirect
        // If it's AJAX, it will return JSON
        if (is_array($result)) {
            echo json_encode($result);
        }
    }
    if($action == 'reset_password'){
        $result = $auth->reset_password();
        echo json_encode($result);
    }
    if($action == 'verify_email'){
        $result = $auth->verify_email($_GET['token'] ?? '');
        echo json_encode($result);
    }
} else {
    // Use old admin class for other actions (legacy support)
    include '../classes/admin_class.php';
    $crud = new Action();
    
    if($action == 'login2'){
        $login = $crud->login2();
        if($login)
            echo $login;
    }
    if($action == 'logout2'){
        $logout = $crud->logout2();
        if($logout)
            echo $logout;
    }
    if($action == 'save_user'){
        $save = $crud->save_user();
        if($save)
            echo $save;
    }
    if($action == 'delete_user'){
        $save = $crud->delete_user();
        if($save)
            echo $save;
    }
    if($action == 'signup'){
        $save = $crud->signup();
        if($save)
            echo $save;
    }
    if($action == 'update_account'){
        $save = $crud->update_account();
        if($save)
            echo $save;
    }
    if($action == "save_settings"){
        $save = $crud->save_settings();
        if($save)
            echo $save;
    }
    if($action == "save_category"){
        $save = $crud->save_category();
        if($save)
            echo $save;
    }
    if($action == "delete_category"){
        $delete = $crud->delete_category();
        if($delete)
            echo $delete;
    }
    if($action == "save_house"){
        $save = $crud->save_house();
        if($save)
            echo $save;
    }
    if($action == "delete_house"){
        $save = $crud->delete_house();
        if($save)
            echo $save;
    }
    if($action == "save_tenant"){
        $save = $crud->save_tenant();
        if($save)
            echo $save;
    }
    if($action == "delete_tenant"){
        $save = $crud->delete_tenant();
        if($save)
            echo $save;
    }
    if($action == "get_tdetails"){
        $get = $crud->get_tdetails();
        if($get)
            echo $get;
    }
    if($action == "save_payment"){
        $save = $crud->save_payment();
        if($save)
            echo $save;
    }
    if($action == "delete_payment"){
        $save = $crud->delete_payment();
        if($save)
            echo $save;
    }
    
    // New listing management actions
    if($action == "create_listing"){
        $result = create_listing();
        echo json_encode($result);
    }
    if($action == "update_listing"){
        $result = update_listing();
        echo json_encode($result);
    }
    if($action == "get_listing_details"){
        $result = get_listing_details();
        echo json_encode($result);
    }
    if($action == "delete_listing"){
        $result = delete_listing();
        echo $result;
    }
    
    // Booking management actions
    if($action == "request_booking"){
        $result = request_booking();
        echo json_encode($result);
    }
    if($action == "get_booking_details"){
        $result = get_booking_details();
        echo json_encode($result);
    }
    if($action == "update_booking_status"){
        $result = update_booking_status();
        echo $result;
    }
    
    // Admin moderation actions
    if($action == "moderate_listing"){
        $result = moderate_listing();
        echo $result;
    }
    
    // Admin user management actions
    if($action == "get_user_details"){
        $result = get_user_details();
        echo json_encode($result);
    }
    if($action == "update_user_status"){
        $result = update_user_status();
        echo $result;
    }
    if($action == "delete_user"){
        $result = delete_user();
        echo $result;
    }
    
    // Admin complaint management actions
    if($action == "get_complaint_details"){
        $result = get_complaint_details();
        echo json_encode($result);
    }
    if($action == "update_complaint_status"){
        $result = update_complaint_status();
        echo $result;
    }
    if($action == "file_complaint"){
        $result = file_complaint();
        echo json_encode($result);
    }
    if($action == "get_student_complaint_details"){
        $result = get_student_complaint_details();
        echo json_encode($result);
    }
    
    // Messaging actions
    if($action == "get_conversation_details"){
        $result = get_conversation_details();
        echo json_encode($result);
    }
    if($action == "send_message"){
        $result = send_message();
        echo $result;
    }
    if($action == "mark_messages_read"){
        $result = mark_messages_read();
        echo $result;
    }
    if($action == "get_thread_messages"){
        $result = get_thread_messages();
        echo json_encode($result);
    }
    if($action == "send_thread_message"){
        $result = send_thread_message();
        echo json_encode($result);
    }
    
    // Profile management actions
    if($action == "update_profile"){
        $result = update_profile();
        echo json_encode($result);
    }
    if($action == "get_user_activity"){
        $result = get_user_activity();
        echo json_encode($result);
    }
    
    // Message thread creation
    if($action == "create_message_thread"){
        $result = create_message_thread();
        echo json_encode($result);
    }
    
    // Review management actions
    if($action == "get_review_stats"){
        $result = get_review_stats();
        echo json_encode($result);
    }
    if($action == "get_reviews"){
        $result = get_reviews();
        echo json_encode($result);
    }
    if($action == "get_review_targets"){
        $result = get_review_targets();
        echo json_encode($result);
    }
    if($action == "submit_review"){
        $result = submit_review();
        echo json_encode($result);
    }
    if($action == "delete_review"){
        $result = delete_review();
        echo json_encode($result);
    }
}

// Cloudinary helper (used for image uploads across the app)
function cloudinary_config() {
    $cfg = [
        'api_key' => '146229295188434',
        'api_secret' => 'KAX1kKOEXP3AOyr4KWfWdZIlID4',
        'cloud_name' => 'dguaflt7t'
    ];
    $env = getenv('CLOUDINARY_URL');
    if ($env && preg_match("/cloudinary:\/\/(.*?):(.*?)@(.*)/", $env, $m)) {
        $cfg['api_key'] = $m[1];
        $cfg['api_secret'] = $m[2];
        $cfg['cloud_name'] = $m[3];
    }
    return $cfg;
}

function upload_to_cloudinary_api($fileTmpPath, $folder = 'uploads') {
    try {
        if (!file_exists($fileTmpPath)) return null;
        $cfg = cloudinary_config();
        $timestamp = time();
        $params_to_sign = 'folder=' . $folder . '&timestamp=' . $timestamp;
        $signature = sha1($params_to_sign . $cfg['api_secret']);
        $url = 'https://api.cloudinary.com/v1_1/' . $cfg['cloud_name'] . '/image/upload';
        $postFields = [
            'file' => new \CURLFile($fileTmpPath),
            'api_key' => $cfg['api_key'],
            'timestamp' => $timestamp,
            'folder' => $folder,
            'signature' => $signature
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) return null;
        $json = json_decode($result, true);
        return $json;
    } catch (\Throwable $e) {
        return null;
    }
}

// New listing management functions
function create_listing() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'owner') {
        return ['status' => 'error', 'message' => 'Unauthorized'];
    }
    
    $owner_id = $_SESSION['login_id'];
    $title = $_POST['title'];
    $address = $_POST['address'];
    $price_lkr = $_POST['price_lkr'];
    $room_type = $_POST['room_type'];
    $gender_pref = $_POST['gender_pref'];
    $description = $_POST['description'];
    $facilities = $_POST['facilities_json'] ?? '[]';
    $lat = $_POST['lat'] ?? null;
    $lng = $_POST['lng'] ?? null;
    
    $sql = "INSERT INTO listings (owner_id, title, address, price_lkr, room_type, gender_pref, description, facilities, lat, lng, availability_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'under_review')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdssssdd", $owner_id, $title, $address, $price_lkr, $room_type, $gender_pref, $description, $facilities, $lat, $lng);
    
    if ($stmt->execute()) {
        $listing_id = $conn->insert_id;
        
        // Handle image uploads (Cloudinary preferred)
        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = '../assets/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $uploaded_count = 0;
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmp = $_FILES['images']['tmp_name'][$i];
                    $cloud = upload_to_cloudinary_api($tmp, 'listing_images');
                    $url = '';
                    if ($cloud && isset($cloud['secure_url'])) {
                        $url = $cloud['secure_url'];
                    } else {
                        // fallback to local
                        $file_name = time() . '_' . $_FILES['images']['name'][$i];
                        $file_path = $upload_dir . $file_name;
                        if (move_uploaded_file($tmp, $file_path)) {
                            $url = $file_path;
                        }
                    }
                    if ($url) {
                        $media_sql = "INSERT INTO media (listing_id, url, type, position) VALUES (?, ?, 'image', ?)";
                        $media_stmt = $conn->prepare($media_sql);
                        $media_stmt->bind_param("isi", $listing_id, $url, $uploaded_count);
                        if ($media_stmt->execute()) { $uploaded_count++; }
                    }
                }
            }
        }
        
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'create_listing', 'listing', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['title' => $title, 'price' => $price_lkr]);
        $audit_stmt->bind_param("iis", $owner_id, $listing_id, $meta);
        $audit_stmt->execute();
        
        return ['status' => 'success', 'message' => 'Listing created successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to create listing'];
    }
}

function update_listing() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'owner') {
        return ['status' => 'error', 'message' => 'Unauthorized'];
    }
    
    $listing_id = $_POST['listing_id'];
    $owner_id = $_SESSION['login_id'];
    $title = $_POST['title'];
    $address = $_POST['address'];
    $price_lkr = $_POST['price_lkr'];
    $room_type = $_POST['room_type'];
    $gender_pref = $_POST['gender_pref'];
    $description = $_POST['description'];
    $facilities = $_POST['facilities_json'] ?? '[]';
    $lat = $_POST['lat'] ?? null;
    $lng = $_POST['lng'] ?? null;
    
    // Verify ownership
    $check_sql = "SELECT owner_id FROM listings WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $listing_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        return ['status' => 'error', 'message' => 'Listing not found'];
    }
    
    $listing = $check_result->fetch_assoc();
    if ($listing['owner_id'] != $owner_id) {
        return ['status' => 'error', 'message' => 'Unauthorized'];
    }
    
    // Update listing
    $sql = "UPDATE listings SET title = ?, address = ?, price_lkr = ?, room_type = ?, gender_pref = ?, description = ?, facilities = ?, lat = ?, lng = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssssddi", $title, $address, $price_lkr, $room_type, $gender_pref, $description, $facilities, $lat, $lng, $listing_id);
    
    if ($stmt->execute()) {
        // Handle removed images
        if (!empty($_POST['removed_images'])) {
            $removed_images = json_decode($_POST['removed_images'], true);
            foreach ($removed_images as $image_id) {
                // Get file path before deleting
                $media_sql = "SELECT url FROM media WHERE id = ? AND listing_id = ?";
                $media_stmt = $conn->prepare($media_sql);
                $media_stmt->bind_param("ii", $image_id, $listing_id);
                $media_stmt->execute();
                $media_result = $media_stmt->get_result();
                
                if ($media_result->num_rows > 0) {
                    $media = $media_result->fetch_assoc();
                    $file_path = $media['url'];
                    
                    // Delete local file only (Cloudinary remote URLs are not removed here)
                    if (strpos($file_path, 'http') !== 0) {
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }
                    
                    // Delete from database
                    $delete_sql = "DELETE FROM media WHERE id = ?";
                    $delete_stmt = $conn->prepare($delete_sql);
                    $delete_stmt->bind_param("i", $image_id);
                    $delete_stmt->execute();
                }
            }
        }
        
        // Handle new image uploads (Cloudinary preferred)
        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = '../assets/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Get current max position
            $position_sql = "SELECT MAX(position) as max_pos FROM media WHERE listing_id = ?";
            $position_stmt = $conn->prepare($position_sql);
            $position_stmt->bind_param("i", $listing_id);
            $position_stmt->execute();
            $position_result = $position_stmt->get_result();
            $max_position = $position_result->fetch_assoc()['max_pos'] ?? -1;
            
            $uploaded_count = 0;
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmp = $_FILES['images']['tmp_name'][$i];
                    $cloud = upload_to_cloudinary_api($tmp, 'listing_images');
                    $url = '';
                    if ($cloud && isset($cloud['secure_url'])) {
                        $url = $cloud['secure_url'];
                    } else {
                        // fallback to local
                        $file_name = time() . '_' . $_FILES['images']['name'][$i];
                        $file_path = $upload_dir . $file_name;
                        if (move_uploaded_file($tmp, $file_path)) {
                            $url = $file_path;
                        }
                    }
                    if ($url) {
                        $media_sql = "INSERT INTO media (listing_id, url, type, position) VALUES (?, ?, 'image', ?)";
                        $media_stmt = $conn->prepare($media_sql);
                        $new_position = $max_position + 1 + $uploaded_count;
                        $media_stmt->bind_param("isi", $listing_id, $url, $new_position);
                        if ($media_stmt->execute()) { $uploaded_count++; }
                    }
                }
            }
        }
        
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'update_listing', 'listing', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['title' => $title, 'price' => $price_lkr]);
        $audit_stmt->bind_param("iis", $owner_id, $listing_id, $meta);
        $audit_stmt->execute();
        
        return ['status' => 'success', 'message' => 'Listing updated successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to update listing'];
    }
}

function get_listing_details() {
    include '../config/db_connect.php';
    
    $listing_id = $_POST['id'];
    
    $sql = "SELECT l.*, u.email as owner_email,
                   (SELECT url FROM media WHERE listing_id = l.id ORDER BY position LIMIT 1) as main_image
            FROM listings l 
            INNER JOIN users u ON l.owner_id = u.id 
            WHERE l.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listing_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return ['status' => 'error', 'message' => 'Listing not found'];
    }
}

function delete_listing() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return 0;
    }
    
    $listing_id = $_POST['id'];
    $user_id = $_SESSION['login_id'];
    $user_role = $_SESSION['login_role'];
    
    // Check if user owns the listing or is admin
    $check_sql = "SELECT owner_id FROM listings WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $listing_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $listing = $result->fetch_assoc();
        if ($listing['owner_id'] != $user_id && $user_role != 'admin') {
            return 0;
        }
    } else {
        return 0;
    }
    
    // Delete media files
    $media_sql = "SELECT url FROM media WHERE listing_id = ?";
    $media_stmt = $conn->prepare($media_sql);
    $media_stmt->bind_param("i", $listing_id);
    $media_stmt->execute();
    $media_result = $media_stmt->get_result();
    
    while ($media = $media_result->fetch_assoc()) {
        $file_path = $media['url'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Delete from database
    $delete_sql = "DELETE FROM listings WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $listing_id);
    
    if ($delete_stmt->execute()) {
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id) VALUES (?, 'delete_listing', 'listing', ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $audit_stmt->bind_param("ii", $user_id, $listing_id);
        $audit_stmt->execute();
        
        return 1;
    } else {
        return 0;
    }
}

// Booking management functions
function request_booking() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'student') {
        return ['status' => 'error', 'message' => 'Unauthorized'];
    }
    
    $student_id = $_SESSION['login_id'];
    $listing_id = $_POST['listing_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'] ?: null;
    $student_note = $_POST['student_note'] ?: null;
    
    // Get listing price
    $listing_sql = "SELECT price_lkr FROM listings WHERE id = ?";
    $listing_stmt = $conn->prepare($listing_sql);
    $listing_stmt->bind_param("i", $listing_id);
    $listing_stmt->execute();
    $listing_result = $listing_stmt->get_result();
    
    if ($listing_result->num_rows == 0) {
        return ['status' => 'error', 'message' => 'Property not found. Please refresh the page and try again.'];
    }
    
    $listing = $listing_result->fetch_assoc();
    $monthly_rent = $listing['price_lkr'];
    
    // Calculate total amount
    $total_amount = $monthly_rent;
    if ($end_date) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $months = ceil($start->diff($end)->days / 30);
        $total_amount = $monthly_rent * $months;
    }
    
    // Check for overlapping bookings
    $overlap_sql = "SELECT COUNT(*) as count FROM bookings 
                   WHERE listing_id = ? AND status IN ('pending', 'approved') 
                   AND ((start_date <= ? AND (end_date IS NULL OR end_date >= ?)) 
                   OR (start_date <= ? AND (end_date IS NULL OR end_date >= ?)))";
    $overlap_stmt = $conn->prepare($overlap_sql);
    
    // Prepare variables for bind_param (must be variables, not expressions)
    $end_date_check1 = $end_date ?: $start_date;
    $end_date_check2 = $end_date ?: $start_date;
    $overlap_stmt->bind_param("issss", $listing_id, $end_date_check1, $start_date, $start_date, $end_date_check2);
    $overlap_stmt->execute();
    $overlap_result = $overlap_stmt->get_result();
    $overlap_count = $overlap_result->fetch_assoc()['count'];
    
    if ($overlap_count > 0) {
        return ['status' => 'error', 'message' => 'This property is not available for the selected dates. Please try different dates or select another property.'];
    }
    
    // Insert booking
    $booking_sql = "INSERT INTO bookings (listing_id, student_id, start_date, end_date, student_note, total_amount, status) 
                   VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $booking_stmt = $conn->prepare($booking_sql);
    $booking_stmt->bind_param("iisssd", $listing_id, $student_id, $start_date, $end_date, $student_note, $total_amount);
    
    if ($booking_stmt->execute()) {
        $booking_id = $conn->insert_id;
        
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'request_booking', 'booking', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['listing_id' => $listing_id, 'start_date' => $start_date, 'end_date' => $end_date]);
        $audit_stmt->bind_param("iis", $student_id, $booking_id, $meta);
        $audit_stmt->execute();
        
        return ['status' => 'success', 'message' => 'Booking request sent successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to create booking request. Please try again or contact support if the problem persists.'];
    }
}

function get_booking_details() {
    include '../config/db_connect.php';
    
    $booking_id = $_POST['id'];
    $user_id = $_SESSION['login_id'];
    $user_role = $_SESSION['login_role'];
    
    if ($user_role == 'student') {
        $sql = "SELECT b.*, l.title, l.address, l.price_lkr 
                FROM bookings b 
                INNER JOIN listings l ON b.listing_id = l.id 
                WHERE b.id = ? AND b.student_id = ?";
    } else {
        $sql = "SELECT b.*, l.title, l.address, l.price_lkr 
                FROM bookings b 
                INNER JOIN listings l ON b.listing_id = l.id 
                WHERE b.id = ? AND l.owner_id = ?";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return ['status' => 'error', 'message' => 'Booking not found'];
    }
}

function update_booking_status() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return 0;
    }
    
    $booking_id = $_POST['id'];
    $new_status = $_POST['status'];
    $user_id = $_SESSION['login_id'];
    $user_role = $_SESSION['login_role'];
    
    // Check if user has permission to update this booking
    if ($user_role == 'student') {
        $check_sql = "SELECT id FROM bookings WHERE id = ? AND student_id = ?";
    } else {
        $check_sql = "SELECT b.id FROM bookings b 
                     INNER JOIN listings l ON b.listing_id = l.id 
                     WHERE b.id = ? AND l.owner_id = ?";
    }
    
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $booking_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        return 0;
    }
    
    // Update booking status
    $update_sql = "UPDATE bookings SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $booking_id);
    
    if ($update_stmt->execute()) {
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'update_booking_status', 'booking', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['new_status' => $new_status]);
        $audit_stmt->bind_param("iis", $user_id, $booking_id, $meta);
        $audit_stmt->execute();
        
        return 1;
    } else {
        return 0;
    }
}

// Admin moderation functions
function moderate_listing() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'admin') {
        return 0;
    }
    
    $listing_id = $_POST['id'];
    $new_status = $_POST['status'];
    $reason = $_POST['reason'] ?? null;
    $admin_id = $_SESSION['login_id'];
    
    // Update listing status
    $update_sql = "UPDATE listings SET availability_status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $listing_id);
    
    if ($update_stmt->execute()) {
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'moderate_listing', 'listing', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['new_status' => $new_status, 'reason' => $reason]);
        $audit_stmt->bind_param("iis", $admin_id, $listing_id, $meta);
        $audit_stmt->execute();
        
        return 1;
    } else {
        return 0;
    }
}

// User management functions
function get_user_details() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'admin') {
        return ['status' => 'error', 'message' => 'Unauthorized'];
    }
    
    $user_id = $_POST['id'];
    
    $sql = "SELECT u.*, 
                   sp.full_name as student_name, sp.university, sp.gender, sp.student_id_doc,
                   op.full_name as owner_name, op.verified, op.nic_doc,
                   ap.full_name as admin_name,
                   (SELECT COUNT(*) FROM listings WHERE owner_id = u.id) as listing_count,
                   (SELECT COUNT(*) FROM bookings WHERE student_id = u.id) as booking_count
            FROM users u 
            LEFT JOIN student_profiles sp ON u.id = sp.user_id
            LEFT JOIN owner_profiles op ON u.id = op.user_id
            LEFT JOIN admin_profiles ap ON u.id = ap.user_id
            WHERE u.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user['full_name'] = $user['student_name'] ?: $user['owner_name'] ?: $user['admin_name'] ?: 'Unknown';
        return $user;
    } else {
        return ['status' => 'error', 'message' => 'User not found'];
    }
}

function update_user_status() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'admin') {
        return 0;
    }
    
    $user_id = $_POST['id'];
    $new_status = $_POST['status'];
    $reason = $_POST['reason'] ?? null;
    $admin_id = $_SESSION['login_id'];
    
    // Update user status
    $update_sql = "UPDATE users SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $user_id);
    
    if ($update_stmt->execute()) {
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'update_user_status', 'user', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['new_status' => $new_status, 'reason' => $reason]);
        $audit_stmt->bind_param("iis", $admin_id, $user_id, $meta);
        $audit_stmt->execute();
        
        return 1;
    } else {
        return 0;
    }
}

function delete_user() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'admin') {
        return 0;
    }
    
    $user_id = $_POST['id'];
    $admin_id = $_SESSION['login_id'];
    
    // Check if user is admin (prevent deleting admins)
    $check_sql = "SELECT role FROM users WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['role'] == 'admin') {
            return 0; // Cannot delete admin users
        }
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete related records first
        $conn->query("DELETE FROM student_profiles WHERE user_id = $user_id");
        $conn->query("DELETE FROM owner_profiles WHERE user_id = $user_id");
        $conn->query("DELETE FROM admin_profiles WHERE user_id = $user_id");
        $conn->query("DELETE FROM listings WHERE owner_id = $user_id");
        $conn->query("DELETE FROM bookings WHERE student_id = $user_id");
        $conn->query("DELETE FROM messages WHERE sender_id = $user_id");
        $conn->query("DELETE FROM complaints WHERE by_student_id = $user_id OR against_owner_id = $user_id");
        $conn->query("DELETE FROM reviews WHERE by_user_id = $user_id OR target_user_id = $user_id");
        
        // Delete user
        $delete_sql = "DELETE FROM users WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_id);
        $delete_stmt->execute();
        
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'delete_user', 'user', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['deleted_user_id' => $user_id]);
        $audit_stmt->bind_param("iis", $admin_id, $user_id, $meta);
        $audit_stmt->execute();
        
        $conn->commit();
        return 1;
    } catch (Exception $e) {
        $conn->rollback();
        return 0;
    }
}

// Complaint management functions
function get_complaint_details() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'admin') {
        return ['status' => 'error', 'message' => 'Unauthorized'];
    }
    
    $complaint_id = $_POST['id'];
    
    $sql = "SELECT c.*, 
                   sp.full_name as student_name, sp.university,
                   op.full_name as owner_name,
                   l.title as listing_title, l.address as listing_address,
                   ap.full_name as resolver_name
            FROM complaints c 
            LEFT JOIN users u1 ON c.by_student_id = u1.id
            LEFT JOIN student_profiles sp ON u1.id = sp.user_id
            LEFT JOIN users u2 ON c.against_owner_id = u2.id
            LEFT JOIN owner_profiles op ON u2.id = op.user_id
            LEFT JOIN listings l ON c.listing_id = l.id
            LEFT JOIN users u3 ON c.resolver_admin_id = u3.id
            LEFT JOIN admin_profiles ap ON u3.id = ap.user_id
            WHERE c.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return ['status' => 'error', 'message' => 'Complaint not found'];
    }
}

function update_complaint_status() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'admin') {
        return 0;
    }
    
    $complaint_id = $_POST['id'];
    $new_status = $_POST['status'];
    $notes = $_POST['notes'] ?? null;
    $admin_id = $_SESSION['login_id'];
    
    // Update complaint status
    $update_sql = "UPDATE complaints SET status = ?, resolution_notes = ?, resolver_admin_id = ?";
    
    if ($new_status == 'resolved') {
        $update_sql .= ", resolved_at = NOW()";
    } else if ($new_status == 'under_review') {
        $update_sql .= ", resolved_at = NULL";
    }
    
    $update_sql .= " WHERE id = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssii", $new_status, $notes, $admin_id, $complaint_id);
    
    if ($update_stmt->execute()) {
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'update_complaint_status', 'complaint', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['new_status' => $new_status, 'notes' => $notes]);
        $audit_stmt->bind_param("iis", $admin_id, $complaint_id, $meta);
        $audit_stmt->execute();
        
        return 1;
    } else {
        return 0;
    }
}

// Messaging functions
function get_conversation_details() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not authenticated'];
    }
    
    $thread_id = $_POST['thread_id'];
    $user_id = $_SESSION['login_id'];
    $user_role = $_SESSION['login_role'];
    
    // Verify user has access to this conversation
    $access_sql = "SELECT * FROM message_threads WHERE id = ? AND (owner_id = ? OR student_id = ?)";
    $access_stmt = $conn->prepare($access_sql);
    $access_stmt->bind_param("iii", $thread_id, $user_id, $user_id);
    $access_stmt->execute();
    $access_result = $access_stmt->get_result();
    
    if ($access_result->num_rows == 0) {
        return ['status' => 'error', 'message' => 'Access denied'];
    }
    
    $thread = $access_result->fetch_assoc();
    
    // Get conversation details with other party info
    if ($user_role == 'owner') {
        $details_sql = "SELECT mt.*, sp.full_name as student_name, l.title as listing_title
                       FROM message_threads mt
                       LEFT JOIN student_profiles sp ON mt.student_id = sp.user_id
                       LEFT JOIN listings l ON mt.listing_id = l.id
                       WHERE mt.id = ?";
    } else {
        $details_sql = "SELECT mt.*, op.full_name as owner_name, l.title as listing_title
                       FROM message_threads mt
                       LEFT JOIN owner_profiles op ON mt.owner_id = op.user_id
                       LEFT JOIN listings l ON mt.listing_id = l.id
                       WHERE mt.id = ?";
    }
    
    $details_stmt = $conn->prepare($details_sql);
    $details_stmt->bind_param("i", $thread_id);
    $details_stmt->execute();
    $details_result = $details_stmt->get_result();
    $conversation = $details_result->fetch_assoc();
    
    // Get messages
    $messages_sql = "SELECT m.*, u.role as sender_role
                    FROM messages m
                    LEFT JOIN users u ON m.sender_id = u.id
                    WHERE m.thread_id = ?
                    ORDER BY m.created_at ASC";
    $messages_stmt = $conn->prepare($messages_sql);
    $messages_stmt->bind_param("i", $thread_id);
    $messages_stmt->execute();
    $messages_result = $messages_stmt->get_result();
    
    $messages = [];
    while ($message = $messages_result->fetch_assoc()) {
        $messages[] = $message;
    }
    
    $conversation['messages'] = $messages;
    
    return $conversation;
}

function get_messages() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return json_encode([]);
    }
    
    $other_user_id = $_POST['other_user_id'];
    $current_user_id = $_SESSION['login_id'];
    
    // Get messages between current user and other user
    $sql = "SELECT m.*, u.email as sender_name 
            FROM messages m 
            LEFT JOIN users u ON m.sender_id = u.id 
            WHERE (m.sender_id = ? AND m.receiver_id = ?) 
            OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.date_created ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $current_user_id, $other_user_id, $other_user_id, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => $row['id'],
            'sender_id' => $row['sender_id'],
            'receiver_id' => $row['receiver_id'],
            'message_text' => $row['message_text'],
            'date_created' => date('M d, Y H:i', strtotime($row['date_created'])),
            'sender_name' => $row['sender_name']
        ];
    }
    
    return json_encode($messages);
}

function send_message() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not logged in'];
    }
    
    $receiver_id = $_POST['receiver_id'];
    $message_text = $_POST['message_text'];
    $sender_id = $_SESSION['login_id'];
    
    // Insert message
    $insert_sql = "INSERT INTO messages (sender_id, receiver_id, message_text, date_created) VALUES (?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iis", $sender_id, $receiver_id, $message_text);
    
    if ($insert_stmt->execute()) {
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'send_message', 'message', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['receiver_id' => $receiver_id, 'message_length' => strlen($message_text)]);
        $audit_stmt->bind_param("iis", $sender_id, $conn->insert_id, $meta);
        $audit_stmt->execute();
        
        return ['status' => 'success', 'message' => 'Message sent successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to send message'];
    }
}

function mark_messages_read() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return 0;
    }
    
    $thread_id = $_POST['thread_id'];
    $user_id = $_SESSION['login_id'];
    
    // Mark messages as read (only messages not sent by current user)
    $update_sql = "UPDATE messages SET read_at = NOW() WHERE thread_id = ? AND sender_id != ? AND read_at IS NULL";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $thread_id, $user_id);
    
    return $update_stmt->execute() ? 1 : 0;
}

// Profile management functions
function update_profile() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not authenticated'];
    }
    
    $user_id = $_SESSION['login_id'];
    $user_role = $_SESSION['login_role'];
    
    // Validate current password if changing password
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if ($new_password) {
        if ($new_password !== $confirm_password) {
            return ['status' => 'error', 'message' => 'New passwords do not match'];
        }
        
        if (strlen($new_password) < 6) {
            return ['status' => 'error', 'message' => 'New password must be at least 6 characters'];
        }
        
        // Verify current password
        $current_hash = md5($current_password);
        $check_sql = "SELECT id FROM users WHERE id = ? AND password_hash = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("is", $user_id, $current_hash);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows == 0) {
            return ['status' => 'error', 'message' => 'Current password is incorrect'];
        }
        
        // Update password
        $new_hash = md5($new_password);
        $update_sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_hash, $user_id);
        $update_stmt->execute();
    }
    
    // Update user basic info
    $phone = $_POST['phone'] ?? '';
    if ($phone) {
        $update_sql = "UPDATE users SET phone = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $phone, $user_id);
        $update_stmt->execute();
    }
    
    // Update profile based on role
    if ($user_role == 'student') {
        $full_name = $_POST['full_name'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $university = $_POST['university'] ?? '';
        $date_of_birth = $_POST['date_of_birth'] ?? '';
        $emergency_contact = $_POST['emergency_contact'] ?? '';
        
        $profile_data = [];
        if ($full_name) $profile_data[] = "full_name = '$full_name'";
        if ($gender) $profile_data[] = "gender = '$gender'";
        if ($university) $profile_data[] = "university = '$university'";
        if ($date_of_birth) $profile_data[] = "date_of_birth = '$date_of_birth'";
        if ($emergency_contact) $profile_data[] = "emergency_contact = '$emergency_contact'";
        
        if (!empty($profile_data)) {
            $update_sql = "UPDATE student_profiles SET " . implode(', ', $profile_data) . " WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user_id);
            $update_stmt->execute();
        }
    } else if ($user_role == 'owner') {
        $full_name = $_POST['full_name'] ?? '';
        $business_name = $_POST['business_name'] ?? '';
        
        $profile_data = [];
        if ($full_name) $profile_data[] = "full_name = '$full_name'";
        if ($business_name) $profile_data[] = "business_name = '$business_name'";
        
        if (!empty($profile_data)) {
            $update_sql = "UPDATE owner_profiles SET " . implode(', ', $profile_data) . " WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user_id);
            $update_stmt->execute();
        }
    }
    
    // Log audit
    $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'update_profile', 'user', ?, ?)";
    $audit_stmt = $conn->prepare($audit_sql);
    $meta = json_encode(['updated_fields' => array_keys($_POST)]);
    $audit_stmt->bind_param("iis", $user_id, $user_id, $meta);
    $audit_stmt->execute();
    
    return ['status' => 'success', 'message' => 'Profile updated successfully'];
}

function get_user_activity() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return [];
    }
    
    $user_id = $_POST['user_id'] ?? $_SESSION['login_id'];
    
    // Get recent activities from audit logs
    $activities_sql = "SELECT action, entity, created_at, meta 
                      FROM audit_logs 
                      WHERE actor_user_id = ? 
                      ORDER BY created_at DESC 
                      LIMIT 10";
    $activities_stmt = $conn->prepare($activities_sql);
    $activities_stmt->bind_param("i", $user_id);
    $activities_stmt->execute();
    $activities_result = $activities_stmt->get_result();
    
    $activities = [];
    while ($activity = $activities_result->fetch_assoc()) {
        $title = ucfirst(str_replace('_', ' ', $activity['action']));
        $description = "Performed action: " . $activity['action'];
        
        if ($activity['meta']) {
            $meta = json_decode($activity['meta'], true);
            if (isset($meta['listing_title'])) {
                $description = "Updated listing: " . $meta['listing_title'];
            } else if (isset($meta['new_status'])) {
                $description = "Changed status to: " . $meta['new_status'];
            }
        }
        
        $activities[] = [
            'title' => $title,
            'description' => $description,
            'created_at' => $activity['created_at']
        ];
    }
    
    return $activities;
}

function create_message_thread() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not authenticated'];
    }
    
    $listing_id = $_POST['listing_id'];
    $other_user_id = $_POST['other_user_id'];
    $current_user_id = $_SESSION['login_id'];
    $current_user_role = $_SESSION['login_role'];
    
    // Determine student and owner IDs
    if ($current_user_role == 'student') {
        $student_id = $current_user_id;
        $owner_id = $other_user_id;
    } else {
        $student_id = $other_user_id;
        $owner_id = $current_user_id;
    }
    
    // Check if thread already exists
    $check_sql = "SELECT id FROM message_threads WHERE listing_id = ? AND student_id = ? AND owner_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("iii", $listing_id, $student_id, $owner_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $thread = $check_result->fetch_assoc();
        return ['status' => 'success', 'thread_id' => $thread['id'], 'message' => 'Thread already exists'];
    }
    
    // Create new thread
    $insert_sql = "INSERT INTO message_threads (listing_id, student_id, owner_id, created_at) VALUES (?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iii", $listing_id, $student_id, $owner_id);
    
    if ($insert_stmt->execute()) {
        $thread_id = $conn->insert_id;
        
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'create_message_thread', 'message_thread', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['listing_id' => $listing_id, 'other_user_id' => $other_user_id]);
        $audit_stmt->bind_param("iis", $current_user_id, $thread_id, $meta);
        $audit_stmt->execute();
        
        return ['status' => 'success', 'thread_id' => $thread_id, 'message' => 'Thread created successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to create thread'];
    }
}

// Review management functions
function get_review_stats() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not authenticated'];
    }
    
    $user_id = $_SESSION['login_id'];
    
    // Get total reviews received
    $received_sql = "SELECT COUNT(*) as count, AVG(rating) as avg_rating FROM reviews WHERE target_user_id = ?";
    $received_stmt = $conn->prepare($received_sql);
    $received_stmt->bind_param("i", $user_id);
    $received_stmt->execute();
    $received_result = $received_stmt->get_result();
    $received_stats = $received_result->fetch_assoc();
    
    // Get total reviews given
    $given_sql = "SELECT COUNT(*) as count FROM reviews WHERE by_user_id = ?";
    $given_stmt = $conn->prepare($given_sql);
    $given_stmt->bind_param("i", $user_id);
    $given_stmt->execute();
    $given_result = $given_stmt->get_result();
    $given_stats = $given_result->fetch_assoc();
    
    return [
        'total_reviews' => $received_stats['count'] + $given_stats['count'],
        'avg_rating' => $received_stats['avg_rating'] ?? 0,
        'given_reviews' => $given_stats['count'],
        'received_reviews' => $received_stats['count']
    ];
}

function get_reviews() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not authenticated'];
    }
    
    $user_id = $_SESSION['login_id'];
    $filter = $_POST['filter'] ?? 'all';
    
    $sql = "SELECT r.*, 
                   u1.email as reviewer_email,
                   COALESCE(sp1.full_name, op1.full_name, u1.email) as reviewer_name,
                   u2.email as target_email,
                   COALESCE(sp2.full_name, op2.full_name, u2.email) as target_name,
                   l.title as listing_title
            FROM reviews r
            INNER JOIN users u1 ON r.by_user_id = u1.id
            LEFT JOIN student_profiles sp1 ON u1.id = sp1.user_id
            LEFT JOIN owner_profiles op1 ON u1.id = op1.user_id
            LEFT JOIN users u2 ON r.target_user_id = u2.id
            LEFT JOIN student_profiles sp2 ON u2.id = sp2.user_id
            LEFT JOIN owner_profiles op2 ON u2.id = op2.user_id
            LEFT JOIN listings l ON r.listing_id = l.id
            WHERE 1=1";
    
    $params = [];
    $types = "";
    
    switch ($filter) {
        case 'given':
            $sql .= " AND r.by_user_id = ?";
            $params[] = $user_id;
            $types .= "i";
            break;
        case 'received':
            $sql .= " AND r.target_user_id = ?";
            $params[] = $user_id;
            $types .= "i";
            break;
        case 'properties':
            $sql .= " AND r.listing_id IS NOT NULL AND (r.by_user_id = ? OR r.target_user_id = ?)";
            $params[] = $user_id;
            $params[] = $user_id;
            $types .= "ii";
            break;
        default: // 'all'
            $sql .= " AND (r.by_user_id = ? OR r.target_user_id = ?)";
            $params[] = $user_id;
            $params[] = $user_id;
            $types .= "ii";
            break;
    }
    
    $sql .= " ORDER BY r.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    
    return $reviews;
}

function get_review_targets() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not authenticated'];
    }
    
    $user_id = $_SESSION['login_id'];
    $review_type = $_POST['review_type'];
    
    $targets = [];
    
    switch ($review_type) {
        case 'property':
            // Get properties the user has booked
            $sql = "SELECT DISTINCT l.id, l.title as name 
                    FROM listings l
                    INNER JOIN bookings b ON l.id = b.listing_id
                    WHERE b.student_id = ? AND b.status = 'approved'
                    AND l.id NOT IN (SELECT listing_id FROM reviews WHERE by_user_id = ? AND listing_id IS NOT NULL)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $targets[] = $row;
            }
            break;
            
        case 'owner':
            // Get property owners the user has interacted with
            $sql = "SELECT DISTINCT u.id, COALESCE(op.full_name, u.email) as name
                    FROM users u
                    LEFT JOIN owner_profiles op ON u.id = op.user_id
                    INNER JOIN listings l ON u.id = l.owner_id
                    INNER JOIN bookings b ON l.id = b.listing_id
                    WHERE b.student_id = ? AND b.status = 'approved'
                    AND u.id NOT IN (SELECT target_user_id FROM reviews WHERE by_user_id = ? AND target_user_id IS NOT NULL)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $targets[] = $row;
            }
            break;
            
        case 'student':
            // Get students who have booked the owner's properties
            $sql = "SELECT DISTINCT u.id, COALESCE(sp.full_name, u.email) as name
                    FROM users u
                    LEFT JOIN student_profiles sp ON u.id = sp.user_id
                    INNER JOIN bookings b ON u.id = b.student_id
                    INNER JOIN listings l ON b.listing_id = l.id
                    WHERE l.owner_id = ? AND b.status = 'approved'
                    AND u.id NOT IN (SELECT target_user_id FROM reviews WHERE by_user_id = ? AND target_user_id IS NOT NULL)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $targets[] = $row;
            }
            break;
    }
    
    return $targets;
}

function submit_review() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not authenticated'];
    }
    
    $by_user_id = $_SESSION['login_id'];
    $review_type = $_POST['review_type'];
    $target_id = $_POST['target_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $listing_id = null;
    $target_user_id = null;
    
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        return ['status' => 'error', 'message' => 'Invalid rating'];
    }
    
    // Determine target based on review type
    switch ($review_type) {
        case 'property':
            $listing_id = $target_id;
            // Get owner of the property
            $owner_sql = "SELECT owner_id FROM listings WHERE id = ?";
            $owner_stmt = $conn->prepare($owner_sql);
            $owner_stmt->bind_param("i", $listing_id);
            $owner_stmt->execute();
            $owner_result = $owner_stmt->get_result();
            if ($owner_result->num_rows > 0) {
                $listing = $owner_result->fetch_assoc();
                $target_user_id = $listing['owner_id'];
            }
            break;
            
        case 'owner':
        case 'student':
            $target_user_id = $target_id;
            break;
    }
    
    // Check if review already exists
    $check_sql = "SELECT id FROM reviews WHERE by_user_id = ? AND ";
    if ($listing_id) {
        $check_sql .= "listing_id = ?";
        $check_params = [$by_user_id, $listing_id];
        $check_types = "ii";
    } else {
        $check_sql .= "target_user_id = ?";
        $check_params = [$by_user_id, $target_user_id];
        $check_types = "ii";
    }
    
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param($check_types, ...$check_params);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        return ['status' => 'error', 'message' => 'You have already reviewed this target'];
    }
    
    // Insert review
    $insert_sql = "INSERT INTO reviews (by_user_id, target_user_id, listing_id, rating, comment, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iiiis", $by_user_id, $target_user_id, $listing_id, $rating, $comment);
    
    if ($insert_stmt->execute()) {
        $review_id = $conn->insert_id;
        
        // Update listing rating if it's a property review
        if ($listing_id) {
            $update_sql = "UPDATE listings SET 
                          avg_rating = (SELECT AVG(rating) FROM reviews WHERE listing_id = ?),
                          total_reviews = (SELECT COUNT(*) FROM reviews WHERE listing_id = ?)
                          WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iii", $listing_id, $listing_id, $listing_id);
            $update_stmt->execute();
        }
        
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'submit_review', 'review', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['review_type' => $review_type, 'rating' => $rating, 'target_id' => $target_id]);
        $audit_stmt->bind_param("iis", $by_user_id, $review_id, $meta);
        $audit_stmt->execute();
        
        return ['status' => 'success', 'message' => 'Review submitted successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to submit review'];
    }
}

function delete_review() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not authenticated'];
    }
    
    $review_id = $_POST['review_id'];
    $user_id = $_SESSION['login_id'];
    
    // Check if user owns the review
    $check_sql = "SELECT listing_id FROM reviews WHERE id = ? AND by_user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $review_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        return ['status' => 'error', 'message' => 'Review not found or unauthorized'];
    }
    
    $review = $check_result->fetch_assoc();
    $listing_id = $review['listing_id'];
    
    // Delete review
    $delete_sql = "DELETE FROM reviews WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $review_id);
    
    if ($delete_stmt->execute()) {
        // Update listing rating if it was a property review
        if ($listing_id) {
            $update_sql = "UPDATE listings SET 
                          avg_rating = (SELECT AVG(rating) FROM reviews WHERE listing_id = ?),
                          total_reviews = (SELECT COUNT(*) FROM reviews WHERE listing_id = ?)
                          WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iii", $listing_id, $listing_id, $listing_id);
            $update_stmt->execute();
        }
        
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'delete_review', 'review', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['review_id' => $review_id]);
        $audit_stmt->bind_param("iis", $user_id, $review_id, $meta);
        $audit_stmt->execute();
        
        return ['status' => 'success', 'message' => 'Review deleted successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to delete review'];
    }
}

// Student complaint functions
function file_complaint() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'student') {
        return ['status' => 'error', 'message' => 'Unauthorized'];
    }
    
    $student_id = $_SESSION['login_id'];
    $complaint_type = $_POST['complaint_type'];
    $listing_id = $_POST['listing_id'] ?? null;
    $owner_id = $_POST['owner_id'] ?? null;
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    
    // Validate input
    if (empty($title) || empty($description)) {
        return ['status' => 'error', 'message' => 'Title and description are required'];
    }
    
    if ($complaint_type == 'property' && !$listing_id) {
        return ['status' => 'error', 'message' => 'Property selection is required'];
    }
    
    if ($complaint_type == 'owner' && !$owner_id) {
        return ['status' => 'error', 'message' => 'Owner selection is required'];
    }
    
    // Get owner ID if complaint is about property
    if ($complaint_type == 'property') {
        $listing_result = $conn->query("SELECT owner_id FROM listings WHERE id = $listing_id");
        if ($listing_result->num_rows > 0) {
            $listing = $listing_result->fetch_assoc();
            $owner_id = $listing['owner_id'];
        } else {
            return ['status' => 'error', 'message' => 'Property not found'];
        }
    } else {
        $listing_id = null; // Clear listing_id for owner complaints
    }
    
    // Insert complaint
    $insert_sql = "INSERT INTO complaints (by_student_id, against_owner_id, listing_id, title, description, priority, status, created_at, updated_at) 
                   VALUES (?, ?, ?, ?, ?, ?, 'under_review', NOW(), NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iiisss", $student_id, $owner_id, $listing_id, $title, $description, $priority);
    
    if ($insert_stmt->execute()) {
        $complaint_id = $conn->insert_id;
        
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'file_complaint', 'complaint', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['complaint_type' => $complaint_type, 'priority' => $priority, 'title' => $title]);
        $audit_stmt->bind_param("iis", $student_id, $complaint_id, $meta);
        $audit_stmt->execute();
        
        return ['status' => 'success', 'message' => 'Complaint filed successfully', 'complaint_id' => $complaint_id];
    } else {
        return ['status' => 'error', 'message' => 'Failed to file complaint: ' . $conn->error];
    }
}

function get_student_complaint_details() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id']) || $_SESSION['login_role'] != 'student') {
        return ['status' => 'error', 'message' => 'Unauthorized'];
    }
    
    $complaint_id = $_POST['id'];
    $student_id = $_SESSION['login_id'];
    
    $sql = "SELECT c.*, 
                   op.full_name as owner_name,
                   l.title as listing_title, l.address as listing_address,
                   ap.full_name as resolver_name
            FROM complaints c 
            LEFT JOIN users u2 ON c.against_owner_id = u2.id
            LEFT JOIN owner_profiles op ON u2.id = op.user_id
            LEFT JOIN listings l ON c.listing_id = l.id
            LEFT JOIN users u3 ON c.resolver_admin_id = u3.id
            LEFT JOIN admin_profiles ap ON u3.id = ap.user_id
            WHERE c.id = ? AND c.by_student_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $complaint_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return ['status' => 'error', 'message' => 'Complaint not found'];
    }
}

// New messaging functions for thread-based messaging
function get_thread_messages() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return [];
    }
    
    $thread_id = $_POST['thread_id'];
    $current_user_id = $_SESSION['login_id'];
    
    // Verify user has access to this thread
    $access_sql = "SELECT * FROM message_threads WHERE id = ? AND (student_id = ? OR owner_id = ?)";
    $access_stmt = $conn->prepare($access_sql);
    $access_stmt->bind_param("iii", $thread_id, $current_user_id, $current_user_id);
    $access_stmt->execute();
    $access_result = $access_stmt->get_result();
    
    if ($access_result->num_rows == 0) {
        return [];
    }
    
    // Get messages from the thread
    $sql = "SELECT m.*, u.email as sender_name 
            FROM messages m 
            LEFT JOIN users u ON m.sender_id = u.id 
            WHERE m.thread_id = ?
            ORDER BY m.created_at ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $thread_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => $row['id'],
            'sender_id' => $row['sender_id'],
            'thread_id' => $row['thread_id'],
            'body' => $row['body'],
            'created_at' => date('M d, Y H:i', strtotime($row['created_at'])),
            'sender_name' => $row['sender_name']
        ];
    }
    
    return $messages;
}

function send_thread_message() {
    include '../config/db_connect.php';
    
    if (!isset($_SESSION['login_id'])) {
        return ['status' => 'error', 'message' => 'Not logged in'];
    }
    
    $thread_id = $_POST['thread_id'] ?? null;
    $receiver_id = $_POST['receiver_id'];
    $message_text = $_POST['message_text'];
    $sender_id = $_SESSION['login_id'];
    $sender_role = $_SESSION['login_role'];
    
    // If no thread_id provided, we need to create or find a thread
    if (!$thread_id) {
        // We need listing_id to create a thread - for now, create a simple message
        // This handles direct messaging without a specific listing context
        
        // Determine student and owner IDs based on roles
        if ($sender_role == 'student') {
            $student_id = $sender_id;
            $owner_id = $receiver_id;
        } else {
            $student_id = $receiver_id;
            $owner_id = $sender_id;
        }
        
        // Try to find existing thread between these users (any listing)
        $find_thread_sql = "SELECT id FROM message_threads WHERE student_id = ? AND owner_id = ? ORDER BY created_at DESC LIMIT 1";
        $find_thread_stmt = $conn->prepare($find_thread_sql);
        $find_thread_stmt->bind_param("ii", $student_id, $owner_id);
        $find_thread_stmt->execute();
        $find_thread_result = $find_thread_stmt->get_result();
        
        if ($find_thread_result->num_rows > 0) {
            $thread = $find_thread_result->fetch_assoc();
            $thread_id = $thread['id'];
        } else {
            // Create new thread without specific listing
            $create_thread_sql = "INSERT INTO message_threads (student_id, owner_id, created_at) VALUES (?, ?, NOW())";
            $create_thread_stmt = $conn->prepare($create_thread_sql);
            $create_thread_stmt->bind_param("ii", $student_id, $owner_id);
            
            if ($create_thread_stmt->execute()) {
                $thread_id = $conn->insert_id;
            } else {
                return ['status' => 'error', 'message' => 'Failed to create conversation thread'];
            }
        }
    }
    
    // Verify user has access to this thread
    $access_sql = "SELECT * FROM message_threads WHERE id = ? AND (student_id = ? OR owner_id = ?)";
    $access_stmt = $conn->prepare($access_sql);
    $access_stmt->bind_param("iii", $thread_id, $sender_id, $sender_id);
    $access_stmt->execute();
    $access_result = $access_stmt->get_result();
    
    if ($access_result->num_rows == 0) {
        return ['status' => 'error', 'message' => 'Access denied to this conversation'];
    }
    
    // Insert message
    $insert_sql = "INSERT INTO messages (thread_id, sender_id, body, created_at) VALUES (?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iis", $thread_id, $sender_id, $message_text);
    
    if ($insert_stmt->execute()) {
        // Update thread's last_message_at
        $update_thread_sql = "UPDATE message_threads SET last_message_at = NOW() WHERE id = ?";
        $update_thread_stmt = $conn->prepare($update_thread_sql);
        $update_thread_stmt->bind_param("i", $thread_id);
        $update_thread_stmt->execute();
        
        // Log audit
        $audit_sql = "INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta) VALUES (?, 'send_thread_message', 'message', ?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $meta = json_encode(['thread_id' => $thread_id, 'message_length' => strlen($message_text)]);
        $audit_stmt->bind_param("iis", $sender_id, $conn->insert_id, $meta);
        $audit_stmt->execute();
        
        return ['status' => 'success', 'message' => 'Message sent successfully', 'thread_id' => $thread_id];
    } else {
        return ['status' => 'error', 'message' => 'Failed to send message'];
    }
}

ob_end_flush();
?>