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
</head>
<style>
    body{
        width: 100%;
        height: calc(100%);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    main#main{
        width:100%;
        height: calc(100%);
        background:transparent;
    }
    #register-container{
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .register-card{
        background: white;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
    }
    .register-left{
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .register-right{
        padding: 40px;
    }
    .role-selector{
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    .role-option{
        flex: 1;
        padding: 15px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .role-option.active{
        border-color: #667eea;
        background: #f8f9ff;
        color: #667eea;
    }
    .role-option input[type="radio"]{
        display: none;
    }
    .form-section{
        display: none;
    }
    .form-section.active{
        display: block;
    }
    .form-group{
        margin-bottom: 20px;
    }
    .form-group label{
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }
    .form-control{
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    .form-control:focus{
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .btn-register{
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .btn-register:hover{
        transform: translateY(-2px);
    }
    .login-link{
        text-align: center;
        margin-top: 20px;
        color: #666;
    }
    .login-link a{
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }
    .alert{
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .alert-success{
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger{
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    @media (max-width: 768px) {
        .register-card{
            margin: 10px;
        }
        .register-left{
            padding: 30px 20px;
        }
        .register-right{
            padding: 30px 20px;
        }
    }
</style>

<body>
    <main id="main">
        <div id="register-container">
            <div class="register-card">
                <div class="row g-0">
                    <div class="col-md-5">
                        <div class="register-left">
                            <div class="mb-4">
                                <img src="<?php echo $base_path; ?>images/house.png" alt="Logo" width="80" class="mb-3">
                                <h3><b><?php echo $_SESSION['system']['name'] ?></b></h3>
                            </div>
                            <p class="mb-4">Join our platform to find the perfect student accommodation or list your property for rent.</p>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4><i class="fa fa-graduation-cap"></i></h4>
                                    <small>Students</small>
                                </div>
                                <div class="col-6">
                                    <h4><i class="fa fa-home"></i></h4>
                                    <small>Property Owners</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="register-right">
                            <h4 class="mb-4 text-center">Create Your Account</h4>
                            
                            <div id="alert-container"></div>
                            
                            <form id="register-form">
                                <!-- Role Selection -->
                                <div class="role-selector">
                                    <label class="role-option" for="role_student">
                                        <input type="radio" name="role" value="student" id="role_student" required>
                                        <div>
                                            <i class="fa fa-graduation-cap fa-2x mb-2"></i>
                                            <div><strong>Student</strong></div>
                                            <small>Looking for accommodation</small>
                                        </div>
                                    </label>
                                    <label class="role-option" for="role_owner">
                                        <input type="radio" name="role" value="owner" id="role_owner" required>
                                        <div>
                                            <i class="fa fa-home fa-2x mb-2"></i>
                                            <div><strong>Owner</strong></div>
                                            <small>Renting out property</small>
                                        </div>
                                    </label>
                                </div>

                                <!-- Common Fields -->
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" id="phone">
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password *</label>
                                    <input type="password" class="form-control" name="password" id="password" required minlength="6">
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password *</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                </div>

                                <!-- Student Specific Fields -->
                                <div id="student-fields" class="form-section">
                                    <div class="form-group">
                                        <label for="full_name">Full Name *</label>
                                        <input type="text" class="form-control" name="full_name" id="full_name">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="gender">Gender *</label>
                                        <select class="form-control" name="gender" id="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="university">University *</label>
                                        <input type="text" class="form-control" name="university" id="university" placeholder="e.g., University of Moratuwa">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth</label>
                                        <input type="date" class="form-control" name="date_of_birth" id="date_of_birth">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="emergency_contact">Emergency Contact</label>
                                        <input type="tel" class="form-control" name="emergency_contact" id="emergency_contact">
                                    </div>
                                </div>

                                <!-- Owner Specific Fields -->
                                <div id="owner-fields" class="form-section">
                                    <div class="form-group">
                                        <label for="owner_full_name">Full Name *</label>
                                        <input type="text" class="form-control" name="owner_full_name" id="owner_full_name">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="business_name">Business Name (Optional)</label>
                                        <input type="text" class="form-control" name="business_name" id="business_name">
                                    </div>
                                </div>

                                <button type="submit" class="btn-register">
                                    <i class="fa fa-user-plus"></i> Create Account
                                </button>
                            </form>
                            
                            <div class="login-link">
                                Already have an account? <a href="login.php">Sign In</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Define missing functions
        window.start_load = function(){
            $('body').prepend('<div id="preloader2" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;"><div style="text-align: center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Loading...</div></div>');
        }
        
        window.end_load = function(){
            $('#preloader2').fadeOut('fast', function() {
                $(this).remove();
            });
        }

        $(document).ready(function(){
            // Role selection handler
            $('.role-option').click(function(){
                $('.role-option').removeClass('active');
                $(this).addClass('active');
                $(this).find('input[type="radio"]').prop('checked', true);
                
                // Show/hide relevant fields
                var role = $(this).find('input[type="radio"]').val();
                $('.form-section').removeClass('active');
                
                if(role == 'student'){
                    $('#student-fields').addClass('active');
                    $('#full_name').attr('required', true);
                    $('#gender').attr('required', true);
                    $('#university').attr('required', true);
                    $('#owner_full_name').attr('required', false);
                } else if(role == 'owner'){
                    $('#owner-fields').addClass('active');
                    $('#owner_full_name').attr('required', true);
                    $('#full_name').attr('required', false);
                    $('#gender').attr('required', false);
                    $('#university').attr('required', false);
                }
            });

            // Form submission
            $('#register-form').submit(function(e){
                e.preventDefault();
                
                // Validate passwords match
                if($('#password').val() !== $('#confirm_password').val()){
                    showAlert('Passwords do not match', 'danger');
                    return;
                }
                
                // Prepare form data
                var formData = new FormData(this);
                
                // Map owner full name to full_name for consistency
                if(formData.get('role') == 'owner'){
                    formData.set('full_name', formData.get('owner_full_name'));
                }
                
                // Remove confirm_password from form data
                formData.delete('confirm_password');
                
                start_load();
                
                $.ajax({
                    url: '<?php echo $base_path; ?>api/ajax.php?action=register',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(resp){
                        end_load();
                        try {
                            var response = JSON.parse(resp);
                            if(response.status == 'success'){
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
                        showAlert('Registration failed. Please try again.', 'danger');
                    }
                });
            });
        });

        function showAlert(message, type) {
            var alertHtml = '<div class="alert alert-' + type + '">' + message + '</div>';
            $('#alert-container').html(alertHtml);
        }
    </script>
</body>
</html>
