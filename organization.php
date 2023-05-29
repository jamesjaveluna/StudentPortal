<?php

$page_title = "Organization";
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
  <h1>Organizations</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="./../">Home</a></li>
      <li class="breadcrumb-item active">Organization</li>
    </ol>
  </nav>
</div>

<section class="section Transparency">
            <div class="row">

                <div class="col-xl-6">
                     <!-- Supreme Student Council -->
                     <div class="card">
                       <img src="../assets/img/t-sco.jpg" style="width: auto;height: 230px;object-fit: cover;" class="card-img-top" alt="...">
                       <div class="card-body">
                         <h5 class="card-title">Supreme Student Council</h5>
                         <p class="card-text">The SSC is the highest governing body of the student organization in St. Cecilia's College - Cebu, Inc. The SSC is responsible for planning and organizing various events and activities, as well as managing the student funds.</p>
                         <div class="d-grid gap-2 mt-3">
                           <a href="../organization/SSC" class="btn text-light btn-danger">View</a>
                         </div>
                       </div>
                     </div>
                     <!-- End Supreme Student Council -->
                 </div>

                 <div class="col-md-6">
                    <!-- EleCom Council -->
                     <div class="card">
                       <img src="../assets/img/t-elecom.jpg" style="width: auto;height: 230px;object-fit: cover;" class="card-img-top" alt="...">
                       <div class="card-body">
                         <h5 class="card-title">Election Committee</h5>
                         <p class="card-text">The Department Council organizes activities and events specifically tailored to the needs and interests of their department, as well as manages the departmental funds. The Department Council members are elected by the students in their respective departments.</p>
                         <div class="d-grid gap-2 mt-3">
                           <a href="../organization/BSIT" class="btn text-light btn-danger">View</a>
                         </div>
                       </div>
                     </div>
                     <!-- End EleCom Council -->
                 </div>

                <div class="col-xl-6">
                     <!-- BSCRIM Department Council -->
                     <div class="card">
                       <img src="../assets/img/t-bscrim.jpg" style="width: auto;height: 230px;object-fit: cover;" class="card-img-top" alt="...">
                       <div class="card-body">
                         <h5 class="card-title">BSCRIM Department Council</h5>
                         <p class="card-text">The Department Council organizes activities and events specifically tailored to the needs and interests of their department, as well as manages the departmental funds. The Department Council members are elected by the students in their respective departments.</p>
                         <div class="d-grid gap-2 mt-3">
                           <a href="../organization/BSIT" class="btn text-light btn-danger">View</a>
                         </div>
                       </div>
                     </div>
                     <!-- End BSCRIM Department Council -->
                 </div>

                <div class="col-xl-6">
                     <!-- HTM Department Council -->
                     <div class="card">
                       <img src="../assets/img/t-htm.jpg" style="width: auto;height: 230px;object-fit: cover;" class="card-img-top" alt="...">
                       <div class="card-body">
                         <h5 class="card-title">HTM Department Council</h5>
                         <p class="card-text">The Department Council organizes activities and events specifically tailored to the needs and interests of their department, as well as manages the departmental funds. The Department Council members are elected by the students in their respective departments.</p>
                         <div class="d-grid gap-2 mt-3">
                           <a href="../organization/BSIT" class="btn text-light btn-danger">View</a>
                         </div>
                       </div>
                     </div>
                     <!-- End HTM Department Council -->
                </div>

                <div class="col-xl-6">
                     <!-- BSIT Department Council -->
                     <div class="card">
                       <img src="../assets/img/t-bsit.jpg" style="width: auto;height: 230px;object-fit: cover;" class="card-img-top" alt="...">
                       <div class="card-body">
                         <h5 class="card-title">BSIT Department Council</h5>
                         <p class="card-text">The Department Council organizes activities and events specifically tailored to the needs and interests of their department, as well as manages the departmental funds. The Department Council members are elected by the students in their respective departments.</p>
                         <div class="d-grid gap-2 mt-3">
                           <a href="../organization/BSIT" class="btn text-light btn-danger">View</a>
                         </div>
                       </div>
                     </div>
                     <!-- End BSIT Department Council -->
                 </div>

                <div class="col-xl-6">
                     <!-- BSBA Department Council -->
                     <div class="card">
                       <img src="../assets/img/t-bsit.jpg" style="width: auto;height: 230px;object-fit: cover;" class="card-img-top" alt="...">
                       <div class="card-body">
                         <h5 class="card-title">BSBA Department Council</h5>
                         <p class="card-text">The Department Council organizes activities and events specifically tailored to the needs and interests of their department, as well as manages the departmental funds. The Department Council members are elected by the students in their respective departments.</p>
                         <div class="d-grid gap-2 mt-3">
                           <a href="../organization/BSIT" class="btn text-light btn-danger">View</a>
                         </div>
                       </div>
                     </div>
                     <!-- End BSBA Department Council -->
                 </div>

                <div class="col-xl-6">
                     <!-- Education Department Council -->
                     <div class="card">
                       <img src="../assets/img/t-educ.jpg" style="width: auto;height: 230px;object-fit: cover;" class="card-img-top" alt="...">
                       <div class="card-body">
                         <h5 class="card-title">Education Department Council</h5>
                         <p class="card-text">The Department Council organizes activities and events specifically tailored to the needs and interests of their department, as well as manages the departmental funds. The Department Council members are elected by the students in their respective departments.</p>
                         <div class="d-grid gap-2 mt-3">
                           <a href="../organization/BSIT" class="btn text-light btn-danger">View</a>
                         </div>
                       </div>
                     </div>
                     <!-- End Education Department Council -->
                </div>
            </div>
        </div>
</section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>