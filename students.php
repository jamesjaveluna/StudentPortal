<?php

$page_title = "Students";
$return_url = $_SERVER['REQUEST_URI'];

ob_start();

session_start();

require_once 'class/Admin.php';
$crud = new Admin();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

$user_type = $_SESSION['user']['type'];
$user_panel = $_SESSION['user']['panel'];
$user_permission = isset(json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel']) ? json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel'] : null;

if($user_panel !== 'admin' && in_array('student_view', $user_permission) && $user_permission === null){
    include 'unauthorized.php';
    exit();
}

$students_raw = json_decode($crud->getStudents(), true);

if($students_raw['code'] === 10000){
    $students_data = $students_raw['data'];
}
?>

<div class="pagetitle">
  <h1>Students</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Admin Panel</li>
      <li class="breadcrumb-item active">Students</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

 <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Cecilian Students</h5>

              <!-- Responses -->
              <div id="response"></div>
              <!-- End Responses -->

              <!-- Buttons -->
              <?php

              if($user_type === 'admin' && in_array('student_import', $user_permission)){
                   echo '<div class="col-lg-12 text-end mb-3">
                              <button type="button" id="importBtnExecuter" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bi bi-file-plus me-1"></i> Import</button>
                          </div>';
              }

              ?>
              <!-- End Buttons -->


              <!-- Table with stripped rows -->
              <table class="table datatable">
                
                  <?php 

                  if($user_type === 'admin' && in_array('student_view', $user_permission) && in_array('student_delete', $user_permission)) {
                        echo '<thead>
                          <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Birthdate</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>';
                    } else {
                        echo '<thead>
                          <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Birthdate</th>
                            <th>Course</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>';
                    }

                  if($students_raw['code'] === 10000){

                    foreach($students_data as $student){
                        echo '<tr class="test">';
                        echo '<td>'.$student['StudentID'].'</td>
                                <td>'.$student['FullName'].'</td>
                                <td>'.$student['Birthday'].'</td>
                                <td>'.$student['Course'].'</td>';
                                
                                if($student['type'] !== null){
                                    echo '<td><span class="badge rounded-pill bg-success">Registered User</span></td>';
                                } else {
                                    echo '<td><span class="badge rounded-pill bg-secondary">Not Registered</span></td>';
                                }  

                                if($user_type === 'admin' && in_array('student_delete', $user_permission)) {
                                    echo '<td><button class="btn btn-danger deleteStudentBtn" data-id="'.$student['StudentID'].'" data-name="'.$student['FullName'].'" type="button" data-bs-toggle="modal" data-bs-target="#confirmationModal"><i class="bi bi-eraser"></i></button> </td>';
                                }

                        echo '</tr>';
                    }

                  } else {
                       if(GEN_DEBUG === true && $user_type === 'admin'){
                            echo '<tr><td class="datatable-empty" colspan="6">'.$students_raw['message'].'</td></tr>';
                            echo '<tr><td class="datatable-empty bg-danger text-white" colspan="6"><b>[DEBUG IS ENABLED] </b><br><i class="bi bi-exclamation-circle me-1"></i> Only admin can view this error. You can also disable GEN_DEBUG in config.php </td></tr>';
                            echo '<tr><td class="datatable-empty bg-danger text-white" colspan="6">'.$students_raw['debug'].'</td></tr>';
                       } else {
                           echo '<tr><td class="datatable-empty" colspan="6">'.$students_raw['message'].'</td></tr>';
                       }
                  }
                  ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
 </section>

 <?php
 if(in_array('student_import', $user_permission)) {
 ?>
 <!-- Import Modal -->
 <div class="modal fade" id="importModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
      </div>
      <div class="modal-body">
         <div id="response">
         
         </div>
         <form id="importForm" enctype="multipart/form-data">
             <div class="col-sm-12">
               <input id="operator" type="hidden" value="student">
               <input name="xlsFile" accept=".xls" class="form-control" type="file" id="fileInput" required>
             </div>
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="importButton"> Import</button>
      </div>
    </div>
  </div>
</div>
<!-- End Import Modal-->
<?php
 }

 if(in_array('student_delete', $user_permission)) {
?>
<!-- Remove Modal -->
 <div class="modal fade" id="confirmationModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Deregister User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
      </div>
      <div class="modal-body">
         <div class="alert alert-warning fade show" role="alert">
                <h4 class="alert-heading">Note</h4>
                <p>Registered user cannot be deleted in this page. Please visit <a href="./users.php">User</a> page and deregister the account before deleting.</p>
                <hr>
                <p class="mb-0">Do you want to delete <b><span id="full-name-placeholder"></span></b>?</p>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteStudentBtn" data-bs-dismiss="modal"> Yes, deregister</button>
      </div>
    </div>
  </div>
</div>
<!-- Remove Modal-->
<?php
 }

?>
<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>