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
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><code>debug_view</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span> <span class="badge bg-primary text-white">Moderator</span> <span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can view all bugs when the GEN_DEBUG is enabled in portal settings.</td>
                  </tr>
                </tbody>
              </table>

              <h5 class="card-title mt-5">User</h5>

              <!-- Table with hoverable rows -->
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><code>user_view</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span> <span class="badge bg-primary text-white">Moderator</span> <span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can view all registered users.</td>
                  </tr>
                  <tr>
                    <td><code>user_add</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span></td>
                    <td>Can add user with any user type.</td>
                  </tr>
                  <tr>
                    <td><code>user_add</code></td>
                    <td><span class="badge bg-primary text-white">Moderator</span></td>
                    <td>Can add user with a user type <small><span class="badge bg-info text-white">Officer</span></small> or <small><span class="badge bg-primary text-white">Moderator</span></small>.</td>
                  </tr>
                  <tr>
                    <td><code>user_query</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span></td>
                    <td>Can search a user upon adding.</td>
                  </tr>
                  <tr>
                    <td><code>user_edit</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span></td>
                    <td>Can edit all user details.</td>
                  </tr>
                  <tr>
                    <td><code>user_edit</code></td>
                    <td><span class="badge bg-primary text-white">Moderator</span></td>
                    <td>Can edit user type only to <small><span class="badge bg-info text-white">Officer</span></small> or <small><span class="badge bg-primary text-white">Moderator</span></small>.</td>
                  </tr>
                  <tr>
                    <td><code>user_delete</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span></td>
                    <td>Can delete registered users.</td>
                  </tr>
                  <tr>
                    <td><code>user_verify</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span> <span class="badge bg-primary text-white">Moderator</span> <span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can resend verification to any user.</td>
                  </tr>
                </tbody>
              </table>

              <h5 class="card-title mt-5">Subject</h5>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><code>subject_view</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span> <span class="badge bg-primary text-white">Moderator</span> <span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can view all the subjects.</td>
                  </tr>
                </tbody>
              </table>

              <h5 class="card-title mt-5">Student</h5>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><code>student_view</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span> <span class="badge bg-primary text-white">Moderator</span> <span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can view all college students.</td>
                  </tr>
                  <tr>
                    <td><code>student_import</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span></td> 
                    <td>Can import xls file from school registrar.</td>
                  </tr>
                  <tr>
                    <td><code>student_delete</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span></td> 
                    <td>Can delete student from portal.</td>
                  </tr>
                  </tbody>
             </table>

              <h5 class="card-title mt-5">Calendar</h5>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><code>calendar_view</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span> <span class="badge bg-primary text-white">Moderator</span> <span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can view all the calendars.</td>
                  </tr>
                  <tr>
                    <td><code>calendar_add</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span></td> 
                    <td>Can add event in calendar and set permission.</td>
                  </tr>
                  <tr>
                    <td><code>calendar_add</code></td>
                    <td><span class="badge bg-primary text-white">Moderator</span></td> 
                    <td>Can add event in calendar.</td>
                  </tr>
                  <tr>
                    <td><code>calendar_add</code></td>
                    <td><span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can add event in calendar.</td>
                  </tr>
                  <tr>
                    <td><code>calendar_edit</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span></td> 
                    <td>Can edit any events in calendar.</td>
                  </tr>
                  <tr>
                    <td><code>calendar_edit</code></td>
                    <td><span class="badge bg-primary text-white">Moderator</span></td> 
                    <td>Can edit events created by <small><span class="badge bg-info text-white">Officer</span></small> or <small><span class="badge bg-primary text-white">Moderator</span></small>.</td>
                  </tr>
                  <tr>
                    <td><code>calendar_edit</code></td>
                    <td><span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can edit events created by <small><span class="badge bg-info text-white">Officer</span></small>.</td>
                  </tr>
                  <tr>
                    <td><code>calendar_delete</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span></td> 
                    <td>Can delete any events in calendar.</td>
                  </tr>
                  <tr>
                    <td><code>calendar_delete</code></td>
                    <td><span class="badge bg-primary text-white">Moderator</span></td> 
                    <td>Can delete events created by <small><span class="badge bg-info text-white">Officer</span></small> or <small><span class="badge bg-primary text-white">Moderator</span></small>.</td>
                  </tr>
                  <tr>
                    <td><code>calendar_delete</code></td>
                    <td><span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can delete events created by <small><span class="badge bg-info text-white">Officer</span></small>.</td>
                  </tr>
                </tbody>
             </table>

             <h5 class="card-title mt-5">Support</h5>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><code>support_view</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span> <span class="badge bg-primary text-white">Moderator</span></td> 
                    <td>Can view all topics in support.</td>
                  </tr
                  <tr>
                    <td><code>support_view</code></td>
                    <td><span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can view topics except suggestions and others.</td>
                  </tr>
                  <tr>
                    <td><code>support_edit</code></td>
                    <td><span class="badge bg-danger text-white">Admin</span> <span class="badge bg-primary text-white">Moderator</span></td> 
                    <td>Can edit support ticket to any status.</td>
                  </tr>
                  <tr>
                    <td><code>support_edit</code></td>
                    <td><span class="badge bg-info text-white">Officer</span></td> 
                    <td>Can edit support ticket to any status if it is not yet closed or solved.</td>
                  </tr>
                </tbody>
              </table>
              <!-- End Table with hoverable rows -->
              

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