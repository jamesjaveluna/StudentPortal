<?php

$page_title = "Schedule";
$return_url = $_SERVER['REQUEST_URI'];

ob_start();

session_start();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}
?>

<div class="pagetitle">
  <h1>Funds Transparency</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="./../">Home</a></li>
      <li class="breadcrumb-item active">Funds Tranparency</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
    <!-- Calendar Card -->
        <div class="col-lg-12">
            <div class="row">
                <div class="col-xl-6">
                     <!-- Card with an image overlay -->
                     <div class="card">
                       <img src="../assets/img/card.jpg" class="card-img-top" alt="...">
                       <div class="card-img-overlay">
                         <h5 class="card-title">Supreme Student Council</h5>
                         <p class="card-text">The SSC is the highest governing body of the student organization in St. Cecilia's College - Cebu, Inc. The SSC is responsible for planning and organizing various events and activities, as well as managing the student funds.</p>
                         <br><div class="d-grid gap-2 mt-3">
                           <a href="council.php?page=ssc" class="btn text-light btn-danger btn-danger-force">View</a>
                         </div>
                       </div>
                     </div>
                     <!-- End Card with an image overlay -->
                </div>
                <div class="col-md-6">
                     <!-- Card with an image overlay -->
                     <div class="card">
                       <img src="../assets/img/card.jpg" class="card-img-top" alt="...">
                       <div class="card-img-overlay">
                         <h5 class="card-title">Department Council</h5>
                         <p class="card-text">The Department Council organizes activities and events specifically tailored to the needs and interests of their department, as well as manages the departmental funds. The Department Council members are elected by the students in their respective departments.</p>
                         <div class="d-grid gap-2 mt-3">
                           <a href="council.php?page=bsit" class="btn text-light btn-danger btn-danger-force">View</a>
                         </div>
                       </div>
                     </div>
                     <!-- End Card with an image overlay -->
                </div>
            </div>
        </div>
          

        </div>
       <!-- End Calendar Card -->
</section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>