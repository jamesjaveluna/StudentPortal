<!DOCTYPE html>
<html lang="en">

<?php

require_once('./assets/utility.php');

$utility = new Utility();

$page = basename($_SERVER['PHP_SELF']);
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $page_title; ?> - Cecilian Student Portal</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>document.getElementsByTagName("html")[0].className += " js";</script>

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <?php 

  if($page == "schedule.php"){
    echo '<link href="../assets/vendor/schedule/style.css" rel="stylesheet">';
  }
  ?>

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <?php
        include __DIR__ . "./../assets/inc/header.php";
  ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php
      if($_SESSION['user']['panel'] === 'admin'){
        include __DIR__ . "./../assets/inc/sidebar-admin.php";
      } else {
        include __DIR__ . "./../assets/inc/sidebar.php";
      }
  ?>
  <!-- End Sidebar-->

  <!-- ======= Main ======= -->
  <main id="main" class="main">
    <?php
          echo $content;
    ?>
  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php
        include __DIR__ . "./../assets/inc/footer.php";
  ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center bg-danger justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/vendor/schedule/util.js"></script>
  <script src="../assets/vendor/schedule/main.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>
  <script src="../assets/js/app.js"></script>
  <?php
    if($page == "schedule.php"){
        echo '<script src="../assets/js/schedule.js"></script>';
    }

    //if($_SESSION['user']['type'] === 'admin' || $_SESSION['user']['type'] === 'moderator' || $_SESSION['user']['type'] === 'officer'){
    //    echo '<script src="../assets/js/admin.js"></script>';
    //}

    if($_SESSION['user']['panel'] === 'admin'){
        echo '<script src="../assets/js/admin.js"></script>';
    }

    if($page == "activity.php"){
        echo '<script src="../assets/js/activity.js"></script>';
        echo '<script src="../assets/vendor/fullcalendar/index.global.js"></script>';
    }

    if($page == "support_preview.php" || $page == "support.php"){
        echo '<script src="../assets/js/support.js"></script>';
    }
  ?>

</body>

</html>