<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
// Detect if we're being accessed directly or through index.php
$is_direct_access = strpos($_SERVER['REQUEST_URI'], '/pages/') !== false;
$base_path = $is_direct_access ? '../' : '';
include($base_path . 'config/db_connect.php');
ob_start();
if(!isset($_SESSION['system'])){
    $system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
    foreach($system as $k => $v){
        $_SESSION['system'][$k] = $v;
    }
}
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Register - <?php echo $_SESSION['system']['name'] ?></title>
  <?php include($base_path . 'includes/header.php'); ?>
  <?php 
  if(isset($_SESSION['login_id']))
    header("location:" . $base_path . "index.php?page=dashboard");
  ?>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        line-height: 1.6;
    }

    main#main {
        width: 100%;
        height: 100%;
        background: transparent;
    }

    .register-container {
        max-width: 1000px;
        width: 100%;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 600px;
    }

    .register-left {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .register-left::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .register-left .logo-section {
        position: relative;
        z-index: 2;
        margin-bottom: 30px;
    }

    .register-left .logo-section img {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        margin-bottom: 20px;
    }

    .register-left h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .register-left p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 40px;
        max-width: 300px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        width: 100%;
        max-width: 300px;
    }

    .feature-item {
        text-align: center;
        padding: 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: transform 0.3s ease;
    }

    .feature-item:hover {
        transform: translateY(-5px);
    }

    .feature-item i {
        font-size: 2rem;
        margin-bottom: 10px;
        display: block;
    }

    .feature-item h4 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .feature-item small {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    .register-right {
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .register-right h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 10px;
        text-align: center;
    }

    .register-right .subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 40px;
        font-size: 1rem;
    }

    .form-container {
        position: relative;
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }

    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin: 0 10px;
        transition: all 0.3s ease;
    }

    .step.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: scale(1.1);
    }

    .step.completed {
        background: #28a745;
        color: white;
    }

    .role-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 30px;
    }

    .role-option {
        padding: 20px;
        border: 2px solid #e9ecef;
        border-radius: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .role-option::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .role-option:hover::before {
        left: 100%;
    }

    .role-option:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
    }

    .role-option.active {
        border-color: #667eea;
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
    }

    .role-option input[type="radio"] {
        display: none;
    }

    .role-option i {
        font-size: 2rem;
        margin-bottom: 10px;
        display: block;
    }

    .role-option .role-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    .role-option .role-desc {
        font-size: 0.9rem;
        color: #666;
    }

    .form-section {
        display: none;
        animation: fadeInUp 0.5s ease;
    }

    .form-section.active {
        display: block;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group {
        margin-bottom: 25px;
        position: relative;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }

    .form-group .required {
        color: #e74c3c;
    }

    .form-control {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    .form-control.error {
        border-color: #e74c3c;
        box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
    }

    .form-control.success {
        border-color: #28a745;
        box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.1);
    }

    .input-group {
        position: relative;
    }

    .input-group .form-control {
        padding-right: 50px;
    }

    .input-group .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }

    .toggle-password:hover {
        color: #667eea;
    }

    .password-strength {
        margin-top: 8px;
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .strength-weak { background: #e74c3c; }
    .strength-fair { background: #f39c12; }
    .strength-good { background: #f1c40f; }
    .strength-strong { background: #28a745; }

    .password-requirements {
        margin-top: 8px;
        font-size: 0.85rem;
        color: #666;
    }

    .password-requirements .requirement {
        display: flex;
        align-items: center;
        margin-bottom: 4px;
    }

    .password-requirements .requirement i {
        margin-right: 8px;
        font-size: 0.8rem;
    }

    .password-requirements .requirement.met {
        color: #28a745;
    }

    .password-requirements .requirement.unmet {
        color: #e74c3c;
    }

    .field-error {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
        display: flex;
        align-items: center;
    }

    .field-error i {
        margin-right: 5px;
        font-size: 0.8rem;
    }

    .field-success {
        color: #28a745;
        font-size: 0.85rem;
        margin-top: 5px;
        display: flex;
        align-items: center;
    }

    .field-success i {
        margin-right: 5px;
        font-size: 0.8rem;
    }

    .btn-register {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 18px 30px;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin-top: 20px;
    }

    .btn-register::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-register:hover::before {
        left: 100%;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
    }

    .btn-register:active {
        transform: translateY(0);
    }

    .btn-register:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-register .spinner {
        display: none;
        margin-right: 10px;
    }

    .btn-register.loading .spinner {
        display: inline-block;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .login-link {
        text-align: center;
        margin-top: 30px;
        color: #666;
        font-size: 0.95rem;
    }

    .login-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .login-link a:hover {
        color: #5a6fd8;
        text-decoration: underline;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        animation: slideInDown 0.5s ease;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        width: 0%;
        transition: width 0.5s ease;
        border-radius: 3px;
    }

    @media (max-width: 768px) {
        .register-container {
            grid-template-columns: 1fr;
            margin: 10px;
            border-radius: 20px;
        }

        .register-left {
            padding: 40px 30px;
            order: 2;
        }

        .register-right {
            padding: 40px 30px;
            order: 1;
        }

        .register-left h1 {
            font-size: 2rem;
        }

        .register-right h2 {
            font-size: 1.5rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .role-selector {
            grid-template-columns: 1fr;
        }

        .step {
            width: 35px;
            height: 35px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 10px;
        }

        .register-left,
        .register-right {
            padding: 30px 20px;
        }

        .form-control {
            padding: 14px 16px;
            font-size: 0.95rem;
        }

        .btn-register {
            padding: 16px 25px;
            font-size: 1rem;
        }
    }
</style>

<body>
    <main id="main">
        <div class="register-container">
            <!-- Left Side - Branding -->
            <div class="register-left">
                <div class="logo-section">
                    <img src="<?php echo $base_path; ?>images/house.png" alt="Logo">
                    <h1><?php echo $_SESSION['system']['name'] ?></h1>
                </div>
                <p>Join our platform to find the perfect student accommodation or list your property for rent.</p>
                <div class="features-grid">
                    <div class="feature-item">
                        <i class="fas fa-graduation-cap"></i>
                        <h4>Students</h4>
                        <small>Find your ideal home</small>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-home"></i>
                        <h4>Property Owners</h4>
                        <small>List your properties</small>
                    </div>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="register-right">
                <h2>Create Your Account</h2>
                <p class="subtitle">Join thousands of students and property owners</p>
                
                <div id="alert-container"></div>
                
                <div class="form-container">
                    <!-- Progress Bar -->
                    <div class="progress-bar">
                        <div class="progress-bar-fill" id="progress-bar"></div>
                    </div>
                    
                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step active" id="step-1">1</div>
                        <div class="step" id="step-2">2</div>
                        <div class="step" id="step-3">3</div>
                    </div>

                    <form id="register-form" novalidate>
                        <!-- Step 1: Role Selection -->
                        <div class="form-section active" id="step-1-content">
                            <h3 style="margin-bottom: 20px; color: #333; text-align: center;">Choose Your Role</h3>
                            <div class="role-selector">
                                <label class="role-option" for="role_student">
                                    <input type="radio" name="role" value="student" id="role_student" required>
                                    <i class="fas fa-graduation-cap"></i>
                                    <div class="role-title">Student</div>
                                    <div class="role-desc">Looking for accommodation</div>
                                </label>
                                <label class="role-option" for="role_owner">
                                    <input type="radio" name="role" value="owner" id="role_owner" required>
                                    <i class="fas fa-home"></i>
                                    <div class="role-title">Property Owner</div>
                                    <div class="role-desc">Renting out property</div>
                                </label>
                            </div>
                        </div>

                        <!-- Step 2: Basic Information -->
                        <div class="form-section" id="step-2-content">
                            <h3 style="margin-bottom: 20px; color: #333; text-align: center;">Basic Information</h3>
                            
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" class="form-control" name="email" id="email" required 
                                       placeholder="Enter your email address">
                                <div class="field-error" id="email-error" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span></span>
                                </div>
                                <div class="field-success" id="email-success" style="display: none;">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Email looks good!</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" id="phone" 
                                       placeholder="Enter your phone number">
                                <div class="field-error" id="phone-error" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Password <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" 
                                           required minlength="6" placeholder="Create a strong password">
                                    <button type="button" class="toggle-password" id="toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="password-strength-bar"></div>
                                </div>
                                <div class="password-requirements" id="password-requirements">
                                    <div class="requirement" id="req-length">
                                        <i class="fas fa-times"></i>
                                        <span>At least 6 characters</span>
                                    </div>
                                    <div class="requirement" id="req-uppercase">
                                        <i class="fas fa-times"></i>
                                        <span>One uppercase letter</span>
                                    </div>
                                    <div class="requirement" id="req-lowercase">
                                        <i class="fas fa-times"></i>
                                        <span>One lowercase letter</span>
                                    </div>
                                    <div class="requirement" id="req-number">
                                        <i class="fas fa-times"></i>
                                        <span>One number</span>
                                    </div>
                                </div>
                                <div class="field-error" id="password-error" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" 
                                           required placeholder="Confirm your password">
                                    <button type="button" class="toggle-password" id="toggle-confirm-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="field-error" id="confirm-password-error" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span></span>
                                </div>
                                <div class="field-success" id="confirm-password-success" style="display: none;">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Passwords match!</span>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Profile Information -->
                        <div class="form-section" id="step-3-content">
                            <h3 style="margin-bottom: 20px; color: #333; text-align: center;">Profile Information</h3>
                            
                            <!-- Student Specific Fields -->
                            <div id="student-fields">
                                <div class="form-group">
                                    <label for="full_name">Full Name <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="full_name" id="full_name" 
                                           placeholder="Enter your full name">
                                    <div class="field-error" id="full-name-error" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="gender">Gender <span class="required">*</span></label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div class="field-error" id="gender-error" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="university">University <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="university" id="university" 
                                           placeholder="e.g., University of Moratuwa">
                                    <div class="field-error" id="university-error" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" id="date_of_birth">
                                    <div class="field-error" id="date-of-birth-error" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="emergency_contact">Emergency Contact</label>
                                    <input type="tel" class="form-control" name="emergency_contact" id="emergency_contact" 
                                           placeholder="Emergency contact number">
                                    <div class="field-error" id="emergency-contact-error" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Owner Specific Fields -->
                            <div id="owner-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="owner_full_name">Full Name <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="owner_full_name" id="owner_full_name" 
                                           placeholder="Enter your full name">
                                    <div class="field-error" id="owner-full-name-error" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="business_name">Business Name (Optional)</label>
                                    <input type="text" class="form-control" name="business_name" id="business_name" 
                                           placeholder="Enter your business name">
                                    <div class="field-error" id="business-name-error" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="form-navigation" style="display: flex; gap: 15px; margin-top: 30px;">
                            <button type="button" class="btn btn-secondary" id="prev-btn" style="display: none; flex: 1; padding: 15px; border: 2px solid #e9ecef; background: white; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-primary" id="next-btn" style="flex: 1; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                            <button type="submit" class="btn-register" id="submit-btn" style="display: none;">
                                <i class="fas fa-spinner spinner"></i>
                                <span class="btn-text">Create Account</span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Global variables
        let currentStep = 1;
        const totalSteps = 3;
        let formData = {};
        let validationErrors = {};

        // Define missing functions
        window.start_load = function(){
            $('body').prepend('<div id="preloader2" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;"><div style="text-align: center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Loading...</div></div>');
        }
        
        window.end_load = function(){
            $('#preloader2').fadeOut('fast', function() {
                $(this).remove();
            });
        }

        // Validation functions
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validatePhone(phone) {
            if (!phone) return true; // Optional field
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            return phoneRegex.test(phone.replace(/\s/g, ''));
        }

        function validatePassword(password) {
            const requirements = {
                length: password.length >= 6,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password)
            };
            
            const strength = Object.values(requirements).filter(Boolean).length;
            return { requirements, strength };
        }

        function validateName(name) {
            return name && name.trim().length >= 2;
        }

        function validateDateOfBirth(date) {
            if (!date) return true; // Optional field
            const birthDate = new Date(date);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            return age >= 13 && age <= 100;
        }

        function showFieldError(fieldId, message) {
            const errorDiv = $(`#${fieldId}-error`);
            const successDiv = $(`#${fieldId}-success`);
            const input = $(`#${fieldId}`);
            
            errorDiv.find('span').text(message);
            errorDiv.show();
            successDiv.hide();
            input.addClass('error').removeClass('success');
        }

        function showFieldSuccess(fieldId, message) {
            const errorDiv = $(`#${fieldId}-error`);
            const successDiv = $(`#${fieldId}-success`);
            const input = $(`#${fieldId}`);
            
            successDiv.find('span').text(message);
            successDiv.show();
            errorDiv.hide();
            input.addClass('success').removeClass('error');
        }

        function hideFieldError(fieldId) {
            $(`#${fieldId}-error`).hide();
            $(`#${fieldId}-success`).hide();
            $(`#${fieldId}`).removeClass('error success');
        }

        function updatePasswordStrength() {
            const password = $('#password').val();
            const validation = validatePassword(password);
            const strengthBar = $('#password-strength-bar');
            const requirements = $('#password-requirements .requirement');
            
            // Update strength bar
            const strengthPercentage = (validation.strength / 4) * 100;
            strengthBar.css('width', strengthPercentage + '%');
            
            // Update strength class
            strengthBar.removeClass('strength-weak strength-fair strength-good strength-strong');
            if (validation.strength === 0) {
                strengthBar.addClass('strength-weak');
            } else if (validation.strength === 1) {
                strengthBar.addClass('strength-weak');
            } else if (validation.strength === 2) {
                strengthBar.addClass('strength-fair');
            } else if (validation.strength === 3) {
                strengthBar.addClass('strength-good');
            } else {
                strengthBar.addClass('strength-strong');
            }
            
            // Update requirements
            const reqIds = ['req-length', 'req-uppercase', 'req-lowercase', 'req-number'];
            const reqKeys = ['length', 'uppercase', 'lowercase', 'number'];
            
            reqIds.forEach((id, index) => {
                const req = $(`#${id}`);
                const isMet = validation.requirements[reqKeys[index]];
                req.removeClass('met unmet').addClass(isMet ? 'met' : 'unmet');
                req.find('i').removeClass('fa-check fa-times').addClass(isMet ? 'fa-check' : 'fa-times');
            });
        }

        function validateStep(step) {
            let isValid = true;
            validationErrors = {};

            if (step === 1) {
                const role = $('input[name="role"]:checked').val();
                if (!role) {
                    showAlert('Please select a role', 'warning');
                    return false;
                }
            } else if (step === 2) {
                // Validate email
                const email = $('#email').val();
                if (!email) {
                    showFieldError('email', 'Email is required');
                    isValid = false;
                } else if (!validateEmail(email)) {
                    showFieldError('email', 'Please enter a valid email address');
                    isValid = false;
                } else {
                    showFieldSuccess('email', 'Email looks good!');
                }

                // Validate phone
                const phone = $('#phone').val();
                if (phone && !validatePhone(phone)) {
                    showFieldError('phone', 'Please enter a valid phone number');
                    isValid = false;
                } else if (phone) {
                    hideFieldError('phone');
                }

                // Validate password
                const password = $('#password').val();
                if (!password) {
                    showFieldError('password', 'Password is required');
                    isValid = false;
                } else {
                    const validation = validatePassword(password);
                    if (validation.strength < 2) {
                        showFieldError('password', 'Password is too weak. Please make it stronger.');
                        isValid = false;
                    } else {
                        hideFieldError('password');
                    }
                }

                // Validate confirm password
                const confirmPassword = $('#confirm_password').val();
                if (!confirmPassword) {
                    showFieldError('confirm-password', 'Please confirm your password');
                    isValid = false;
                } else if (password !== confirmPassword) {
                    showFieldError('confirm-password', 'Passwords do not match');
                    isValid = false;
                } else {
                    showFieldSuccess('confirm-password', 'Passwords match!');
                }
            } else if (step === 3) {
                const role = $('input[name="role"]:checked').val();
                
                if (role === 'student') {
                    // Validate student fields
                    const fullName = $('#full_name').val();
                    if (!fullName || !validateName(fullName)) {
                        showFieldError('full-name', 'Please enter your full name');
                        isValid = false;
                    } else {
                        hideFieldError('full-name');
                    }

                    const gender = $('#gender').val();
                    if (!gender) {
                        showFieldError('gender', 'Please select your gender');
                        isValid = false;
                    } else {
                        hideFieldError('gender');
                    }

                    const university = $('#university').val();
                    if (!university || university.trim().length < 2) {
                        showFieldError('university', 'Please enter your university');
                        isValid = false;
                    } else {
                        hideFieldError('university');
                    }

                    const dateOfBirth = $('#date_of_birth').val();
                    if (dateOfBirth && !validateDateOfBirth(dateOfBirth)) {
                        showFieldError('date-of-birth', 'Please enter a valid date of birth');
                        isValid = false;
                    } else if (dateOfBirth) {
                        hideFieldError('date-of-birth');
                    }

                    const emergencyContact = $('#emergency_contact').val();
                    if (emergencyContact && !validatePhone(emergencyContact)) {
                        showFieldError('emergency-contact', 'Please enter a valid emergency contact number');
                        isValid = false;
                    } else if (emergencyContact) {
                        hideFieldError('emergency-contact');
                    }
                } else if (role === 'owner') {
                    // Validate owner fields
                    const fullName = $('#owner_full_name').val();
                    if (!fullName || !validateName(fullName)) {
                        showFieldError('owner-full-name', 'Please enter your full name');
                        isValid = false;
                    } else {
                        hideFieldError('owner-full-name');
                    }

                    const businessName = $('#business_name').val();
                    if (businessName && businessName.trim().length < 2) {
                        showFieldError('business-name', 'Please enter a valid business name');
                        isValid = false;
                    } else if (businessName) {
                        hideFieldError('business-name');
                    }
                }
            }

            return isValid;
        }

        function updateStepIndicator() {
            $('.step').removeClass('active completed');
            
            for (let i = 1; i <= currentStep; i++) {
                if (i < currentStep) {
                    $(`#step-${i}`).addClass('completed');
                } else {
                    $(`#step-${i}`).addClass('active');
                }
            }
        }

        function updateProgressBar() {
            const progress = (currentStep / totalSteps) * 100;
            $('#progress-bar').css('width', progress + '%');
        }

        function showStep(step) {
            $('.form-section').removeClass('active');
            $(`#step-${step}-content`).addClass('active');
            
            // Update navigation buttons
            if (step === 1) {
                $('#prev-btn').hide();
                $('#next-btn').show();
                $('#submit-btn').hide();
            } else if (step === totalSteps) {
                $('#prev-btn').show();
                $('#next-btn').hide();
                $('#submit-btn').show();
            } else {
                $('#prev-btn').show();
                $('#next-btn').show();
                $('#submit-btn').hide();
            }
        }

        function nextStep() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepIndicator();
                    updateProgressBar();
                    showStep(currentStep);
                }
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                updateStepIndicator();
                updateProgressBar();
                showStep(currentStep);
            }
        }

        function showAlert(message, type) {
            const alertHtml = `<div class="alert alert-${type}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle'}"></i>
                ${message}
            </div>`;
            $('#alert-container').html(alertHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $('#alert-container').fadeOut();
            }, 5000);
        }

        function togglePasswordVisibility(inputId, toggleId) {
            const input = $(`#${inputId}`);
            const toggle = $(`#${toggleId}`);
            const icon = toggle.find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        }

        $(document).ready(function(){
            // Initialize
            updateStepIndicator();
            updateProgressBar();
            showStep(1);

            // Role selection handler
            $('.role-option').click(function(){
                $('.role-option').removeClass('active');
                $(this).addClass('active');
                $(this).find('input[type="radio"]').prop('checked', true);
                
                const role = $(this).find('input[type="radio"]').val();
                
                if (role === 'student') {
                    $('#student-fields').show();
                    $('#owner-fields').hide();
                } else if (role === 'owner') {
                    $('#student-fields').hide();
                    $('#owner-fields').show();
                }
            });

            // Real-time validation
            $('#email').on('blur', function() {
                const email = $(this).val();
                if (email && !validateEmail(email)) {
                    showFieldError('email', 'Please enter a valid email address');
                } else if (email) {
                    showFieldSuccess('email', 'Email looks good!');
                } else {
                    hideFieldError('email');
                }
            });

            $('#phone').on('blur', function() {
                const phone = $(this).val();
                if (phone && !validatePhone(phone)) {
                    showFieldError('phone', 'Please enter a valid phone number');
                } else if (phone) {
                    hideFieldError('phone');
                }
            });

            $('#password').on('input', function() {
                updatePasswordStrength();
            });

            $('#confirm_password').on('blur', function() {
                const password = $('#password').val();
                const confirmPassword = $(this).val();
                
                if (confirmPassword && password !== confirmPassword) {
                    showFieldError('confirm-password', 'Passwords do not match');
                } else if (confirmPassword && password === confirmPassword) {
                    showFieldSuccess('confirm-password', 'Passwords match!');
                } else {
                    hideFieldError('confirm-password');
                }
            });

            // Password toggle buttons
            $('#toggle-password').click(function() {
                togglePasswordVisibility('password', 'toggle-password');
            });

            $('#toggle-confirm-password').click(function() {
                togglePasswordVisibility('confirm_password', 'toggle-confirm-password');
            });

            // Navigation buttons
            $('#next-btn').click(nextStep);
            $('#prev-btn').click(prevStep);

            // Form submission
            $('#register-form').submit(function(e){
                e.preventDefault();
                
                if (!validateStep(currentStep)) {
                    return;
                }
                
                // Prepare form data
                const formData = new FormData(this);
                
                // Map owner full name to full_name for consistency
                if (formData.get('role') === 'owner') {
                    formData.set('full_name', formData.get('owner_full_name'));
                }
                
                // Remove confirm_password from form data
                formData.delete('confirm_password');
                
                // Show loading state
                $('#submit-btn').addClass('loading').prop('disabled', true);
                start_load();
                
                $.ajax({
                    url: '<?php echo $base_path; ?>api/ajax.php?action=register',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(resp){
                        end_load();
                        $('#submit-btn').removeClass('loading').prop('disabled', false);
                        
                        try {
                            const response = JSON.parse(resp);
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                setTimeout(function(){
                                    window.location.href = 'login.php';
                                }, 2000);
                            } else {
                                showAlert(response.message, 'danger');
                            }
                        } catch(e) {
                            showAlert('Registration failed. Please try again.', 'danger');
                        }
                    },
                    error: function(){
                        end_load();
                        $('#submit-btn').removeClass('loading').prop('disabled', false);
                        showAlert('Registration failed. Please try again.', 'danger');
                    }
                });
            });

            // Keyboard navigation
            $(document).keydown(function(e) {
                if (e.key === 'Enter') {
                    if (currentStep < totalSteps) {
                        nextStep();
                    } else {
                        $('#register-form').submit();
                    }
                }
            });
        });
    </script>
</body>
</html>
