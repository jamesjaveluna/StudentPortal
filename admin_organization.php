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

require_once 'class/Admin.php';
$crud = new Admin();

$user_type = $_SESSION['user']['type'];
$user_panel = $_SESSION['user']['panel'];
$user_permission = isset(json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel']) ? json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel'] : null;


if($user_panel !== 'admin' && in_array('organization_view', $user_permission) && $user_permission === null){
   include 'unauthorized.php';
    exit();
}

$orgs_raw = json_decode($crud->getOrganizations(), true);

if($orgs_raw['code'] === 10000){
    $orgs_data = $orgs_raw['data'];
}

//var_dump($orgs_raw);

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
<div class="col-lg-12 text-end mb-3">
    <button type="button" id="addBtnExecuter" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg me-1"></i> New</button>
</div>
<section class="section Transparency">
            <div class="row">


                <?php

                foreach($orgs_data as $org){
                    echo '<div class="col-xl-6">
                     <!-- '.$org['name'].' -->
                     <div class="card">
                       <img src="../assets/img/'.$org['cover'].'" style="width: auto;height: 230px;object-fit: cover;" class="card-img-top" alt="cover photo of the organization">
                       <div class="card-body">
                         <h5 class="card-title">'.$org['name'].'</h5>
                         <p class="card-text">'.$org['description'].'</p>
                         <div class="d-grid gap-2 mt-3">
                           <a href="../admin/organization/'.$org['slug'].'" class="btn text-light btn-danger">View</a>
                           <a href="../admin/organization/'.$org['slug'].'" class="btn text-light btn-primary">Edit</a>
                         </div>
                       </div>
                     </div>
                     <!-- End '.$org['name'].' -->
                 </div>';
                }

                ?>
                

                </div>
            </div>
        </div>
</section>



<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>