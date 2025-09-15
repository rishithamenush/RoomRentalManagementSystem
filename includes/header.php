<?php
// Detect if we're being accessed directly from pages/ or through index.php
$is_direct_access = strpos($_SERVER['REQUEST_URI'], '/pages/') !== false;
$base_path = $is_direct_access ? '../' : '';
?>
<meta content="" name="descriptison">
  <meta content="" name="keywords">

  

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/font-awesome/css/all.min.css">


  <!-- Vendor CSS Files -->
  <link href="<?php echo $base_path; ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/DataTables/datatables.min.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/css/jquery.datetimepicker.min.css" rel="stylesheet">
  <link href="<?php echo $base_path; ?>assets/css/select2.min.css" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="<?php echo $base_path; ?>assets/css/style.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="<?php echo $base_path; ?>assets/css/jquery-te-1.4.0.css">
  
  <script src="<?php echo $base_path; ?>assets/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo $base_path; ?>assets/DataTables/datatables.min.js"></script>
  <script src="<?php echo $base_path; ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo $base_path; ?>assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="<?php echo $base_path; ?>assets/vendor/php-email-form/validate.js"></script>
  <script src="<?php echo $base_path; ?>assets/vendor/venobox/venobox.min.js"></script>
  <script src="<?php echo $base_path; ?>assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="<?php echo $base_path; ?>assets/vendor/counterup/counterup.min.js"></script>
  <script src="<?php echo $base_path; ?>assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="<?php echo $base_path; ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_path; ?>assets/js/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_path; ?>assets/js/jquery.datetimepicker.full.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_path; ?>assets/font-awesome/js/all.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_path; ?>assets/js/jquery-te-1.4.0.min.js" charset="utf-8"></script>



