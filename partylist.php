<?php

$page_title = "Users";
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

if($user_panel !== 'admin' && in_array('user_view', $user_permission) && $user_permission === null){
    include 'unauthorized.php';
    exit();
}

$users_raw = json_decode($crud->getUsers(), true);

if($users_raw['code'] === 10000){
    $users_data = $users_raw['data'];
}


?>

<div class="pagetitle">
  <h1>Users</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Admin Panel</li>
      <li class="breadcrumb-item active">Documentation</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

 <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

            <h5 class="card-title">General</h5>
              

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