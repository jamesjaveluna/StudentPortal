<?php

$page_title = "Students";
$return_url = $_SERVER['REQUEST_URI'];

ob_start();

session_start();

require_once 'class/Students.php';
$crud = new Students();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

$user_type = $_SESSION['user']['type'];

$students_data = json_decode($crud->getStudents(), true)['data'];

?>

<div class="pagetitle">
  <h1>Students</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../students.php">Home</a></li>
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

              <?php

              if($user_type === 'admin'){
                   echo '<!-- Buttons -->
                        <div class="row mb-3">
                          <div class="col-lg-9">
                              
                          </div>
                          <div class="col-lg-3">
                              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verticalycentered"><i class="bi bi-file-plus me-1"></i> Import</button>
                              <button type="button" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i> New</button>
                          </div>
                        </div>
                        
                        <!-- End Buttons -->';
              }

              switch($user_type){
                    case 'admin':
                        $buttons = '
                             
                              <button type="button" class="btn btn-danger"><i class="bi bi-trash me-1"></i> Remove</button>';
                    break;

                    case 'moderator':
                        $buttons = '<button type="button" class="btn btn-primary"><i class="bx ri-edit-box-line me-1"></i> Edit</button>';
                    break;

                    case 'officer':
                        $buttons ='<button type="button" class="btn btn-warning"><i class="bx ri-mail-send-line me-1"></i> Resend Email</button>';
                    break;
              }

              ?>


              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Birthdate</th>
                    <th scope="col">Course</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  
                  foreach($students_data as $student){
                    echo '<tr>
                            <td scope="row"><div class="text-success">'.$student['StudentID'].'</div></td>
                            <td><div class="text-success"><strong>'.$student['FullName'].'</div></strong></td>
                            <td><div class="text-success">'.$student['Birthday'].'</div></td>
                            <td><div class="text-success">'.$student['Course'].'</div></td>
                            <td>'.$buttons.'</td>
                         </tr>';
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

 <!-- Import Modal -->
 <div class="modal fade" id="verticalycentered" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
      </div>
      <div class="modal-body">
         <form id="importForm" enctype="multipart/form-data">
            <div class="d-grid gap-2 mt-3">
                <input id="operator" type="hidden" value="student">
                <input id="fileInput" type="file" class="btn btn-secondary" name="xlsFile" accept=".xls" required>
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
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>