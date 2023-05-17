<?php

$page_title = "Profile Page";
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
        <h1 class="text-danger">401</h1>
         <center>
            <h2>The user profile you requested cannot be found.</h2>
        </center>

        <a class="btn" href="#">Report Problem</a>
        <img src="../assets/img/svg/no-user.svg" class="img-fluid py-5" alt="The user profile you requested cannot be found.">
      </section>

</div>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>