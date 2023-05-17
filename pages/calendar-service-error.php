<?php

$page_title = "Error";
$return_url = $_SERVER['REQUEST_URI'];

ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}


?>

<div class="pagetitle">
  <h1>&nbsp;</h1>
</div>

<div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1 class="text-danger">503</h1>
        <center>
            <h2>Calendar Services Unavailable</h2>
        </center>
        
        <a class="btn" href="blank.php">Back to home</a>
        <img src="../assets/img/svg/connection-cut.svg" class="img-fluid py-5" alt="Page Not Found">
      </section>

</div>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>