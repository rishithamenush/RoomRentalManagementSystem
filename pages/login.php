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
  <title>Login - <?php echo $_SESSION['system']['name'] ?></title>
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
    #login-container{
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .login-card{
        background: white;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 800px;
        width: 100%;
    }
    .login-left{
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .login-right{
        padding: 40px;
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
    .btn-login{
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
    .btn-login:hover{
        transform: translateY(-2px);
    }
    .btn-login:disabled{
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    .register-link{
        text-align: center;
        margin-top: 20px;
        color: #666;
    }
    .register-link a{
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
    .forgot-password{
        text-align: right;
        margin-top: 10px;
    }
    .forgot-password a{
        color: #667eea;
        text-decoration: none;
        font-size: 14px;
    }
    .forgot-password a:hover{
        text-decoration: underline;
    }
    @media (max-width: 768px) {
        .login-card{
            margin: 10px;
        }
        .login-left{
            padding: 30px 20px;
        }
        .login-right{
            padding: 30px 20px;
        }
    }
</style>

<body>
    <main id="main">
        <div id="login-container">
            <div class="login-card">
                <div class="row g-0">
                    <div class="col-md-5">
                        <div class="login-left">
                            <div class="mb-4">
                                <img src="<?php echo $base_path; ?>images/house.png" alt="Logo" width="80" class="mb-3">
                                <h3><b><?php echo $_SESSION['system']['name'] ?></b></h3>
                            </div>
                            <p class="mb-4">Welcome back! Sign in to access your account and continue your journey.</p>
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4><i class="fa fa-graduation-cap"></i></h4>
                                    <small>Students</small>
                                </div>
                                <div class="col-4">
                                    <h4><i class="fa fa-home"></i></h4>
                                    <small>Owners</small>
                                </div>
                                <div class="col-4">
                                    <h4><i class="fa fa-cog"></i></h4>
                                    <small>Admins</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="login-right">
                            <h4 class="mb-4 text-center">Sign In</h4>
                            
                            <div id="alert-container"></div>
                            
                            <form id="login-form">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" required>
                                </div>
                                
                                <div class="forgot-password">
                                    <a href="#" onclick="showForgotPassword()">Forgot Password?</a>
                                </div>
                                
                                <button type="submit" class="btn-login">
                                    <i class="fa fa-sign-in-alt"></i> Sign In
                                </button>
                            </form>
                            
                            <div class="register-link">
                                Don't have an account? <a href="register.php">Create Account</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="forgot-password-form">
                        <div class="form-group">
                            <label for="reset_email">Email Address</label>
                            <input type="email" class="form-control" name="email" id="reset_email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            // Form submission
            $('#login-form').submit(function(e){
                e.preventDefault();
                
                var $btn = $(this).find('button[type="submit"]');
                var originalText = $btn.html();
                
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Signing In...');
                
                $.ajax({
                    url: '<?php echo $base_path; ?>api/ajax.php?action=login',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(resp){
                        try {
                            var response = JSON.parse(resp);
                            if(response.status == 'success'){
                                showAlert(response.message, 'success');
                                setTimeout(function(){
                                    window.location.href = '<?php echo $base_path; ?>index.php?page=dashboard';
                                }, 1000);
                            } else {
                                showAlert(response.message, 'danger');
                                $btn.prop('disabled', false).html(originalText);
                            }
                        } catch(e) {
                            // Fallback for old response format
                            if(resp == 1){
                                showAlert('Login successful!', 'success');
                                setTimeout(function(){
                                    window.location.href = '<?php echo $base_path; ?>index.php?page=dashboard';
                                }, 1000);
                            } else {
                                showAlert('Invalid email or password.', 'danger');
                                $btn.prop('disabled', false).html(originalText);
                            }
                        }
                    },
                    error: function(){
                        showAlert('Login failed. Please try again.', 'danger');
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Forgot password form
            $('#forgot-password-form').submit(function(e){
                e.preventDefault();
                
                var $btn = $(this).find('button[type="submit"]');
                var originalText = $btn.html();
                
                $btn.prop('disabled', true).html('Sending...');
                
                $.ajax({
                    url: '<?php echo $base_path; ?>api/ajax.php?action=reset_password',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(resp){
                        try {
                            var response = JSON.parse(resp);
                            showAlert(response.message, response.status == 'success' ? 'success' : 'danger');
                            if(response.status == 'success'){
                                $('#forgotPasswordModal').modal('hide');
                            }
                        } catch(e) {
                            showAlert('Failed to send reset link. Please try again.', 'danger');
                        }
                        $btn.prop('disabled', false).html(originalText);
                    },
                    error: function(){
                        showAlert('Failed to send reset link. Please try again.', 'danger');
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });

        function showAlert(message, type) {
            var alertHtml = '<div class="alert alert-' + type + '">' + message + '</div>';
            $('#alert-container').html(alertHtml);
        }

        function showForgotPassword() {
            $('#forgotPasswordModal').modal('show');
        }
    </script>
</body>
</html>