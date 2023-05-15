<?php

$page_title = "Notification";
$return_url = $_SERVER['REQUEST_URI'];

ob_start();

session_start();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

$avatar = isset($_SESSION['user']['avatar']) ? $_SESSION['user']['avatar'] : 'default-profile.png';   
?>

<div class="pagetitle">
      <h1>Notification</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../">Home</a></li>
          <li class="breadcrumb-item active">Notification</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-6">

          <div class="card">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action active bg-danger" aria-current="true">
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Welcome to Student Portal</h5>
                    <small>3 days ago</small>
                  </div>
                  <p class="mb-1">We are thrilled to welcome you to our Student...</p>
                  <small>And some small print.</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">List group item heading</h5>
                    <small class="text-muted">3 days ago</small>
                  </div>
                  <p class="mb-1">Some placeholder content in a paragraph.</p>
                  <small class="text-muted">And some muted small print.</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">List group item heading</h5>
                    <small class="text-muted">3 days ago</small>
                  </div>
                  <p class="mb-1">Some placeholder content in a paragraph.</p>
                  <small class="text-muted">And some muted small print.</small>
                </a>
              </div>
          </div>

        </div>

        <div class="col-xl-6">
        <div class="card newsfeed">
                   
                   <div class="card-body">
                       <div class="author">
                           <img src="assets/img/post/logo/SCC.gif" style="max-height: 46px;" alt="Profile" class="rounded-circle">
                           <div class="info">
                             <h5>Welcome to Student Portal</h5>
                             <p>25 minutes ago</p>
                           </div>
                       </div>
                     <p>Dear <?php echo $_SESSION['user']['fname']; ?>,<br><br>
Welcome aboard!<br><br>

We are thrilled to welcome you to our Student Portal! This platform will be your go-to source for all your academic needs, and we hope that it will provide you with a seamless experience throughout your educational journey.
<br><br>
The Student Portal has been designed to offer you a centralized hub for all the information and resources you need to succeed. You can access your course materials, submit assignments, view your grades, and communicate with your instructors and peers, all in one place.
<br><br>
We understand that every student has unique needs and requirements, and we are committed to providing you with personalized support. Please do not hesitate to reach out to our support team if you need any assistance or have any questions.
<br><br>
We are excited to have you join our community and look forward to helping you achieve your academic goals.
<br><br>
Best regards,<br>
<b>The Student Portal Team</b></p>
                        <img src="../assets/img/notification/test.png" class="card-img-top" alt="...">
                   </div>

                   <div class="card-footer">
                    <div class="d-grid gap-2 mt-3">
                      <button class="btn btn-primary" type="button"><i class="bi bi-facebook me-1"></i> View Post</button>
                    </div>
                   </div>
            </div>
        </div>
      </div>
    </section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>