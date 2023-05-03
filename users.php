<?php

$page_title = "Users";
$return_url = $_SERVER['REQUEST_URI'];

ob_start();

session_start();

require_once 'class/User.php';
$crud = new User();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

$user_type = $_SESSION['user']['type'];

$users_data = json_decode($crud->getUsers(), true)['data'];

?>

<div class="pagetitle">
  <h1>Users</h1>
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
              <h5 class="card-title">Registered Students</h5>

              <!-- Responses -->
              <div id="response"></div>
              <!-- End Responses -->


              <!-- Buttons -->
              <?php

              if($user_type === 'admin'){
                   echo '<div class="col-lg-12 text-end mb-3">
                              <button type="button" id="addBtnExecuter" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg me-1"></i> New</button>
                          </div>';
              }

              ?>
              <!-- End Buttons -->


              <!-- Table with stripped rows -->
              <table id="userTable" class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Type</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 

                  foreach($users_data as $user){

                    switch($user_type){
                        
                        case 'admin': // Can Edit, Resend Verification, Deregister
                            $buttons = '<button class="btn btn-primary editBtn" data-id="'.$user['id'].'" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bx ri-edit-box-line me-1"></i> Edit</button> ';
                            // For resend verification button
                            if($user['type'] === 'unverified'){
                                $buttons .= '<button class="btn btn-warning resendEmail" data-id="'.$user['id'].'" type="button"><i class="bx ri-mail-send-line me-1"></i> Resend Email</button> ';
                            } 

                            $buttons .= '<button class="btn btn-danger deregisterBtn" data-id="'.$user['id'].'" data-name="'.$user['FullName'].'" type="button" data-bs-toggle="modal" data-bs-target="#confirmationModal"><i class="bi bi-eraser me-1"></i> Deregister</button> ';
                        break;

                        // 
                        case 'moderator': // Can Edit, Resend Verification
                            $buttons = '<button class="btn btn-primary editBtn" data-id="'.$user['id'].'" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bx ri-edit-box-line me-1"></i> Edit</button> ';

                            // For resend verification button
                            if($user['type'] === 'unverified'){
                                $buttons .= '<button class="btn btn-warning resendEmail" data-id="'.$user['id'].'" type="button"><i class="bx ri-mail-send-line me-1"></i> Resend Email</button> ';
                            } 
                            
                        break;

                        case 'officer': // Can Resend Verification
                            $buttons = '';

                            // For resend verification button
                            if($user['type'] === 'unverified'){
                                $buttons = '<button class="btn btn-warning resendEmail" data-id="'.$user['id'].'" type="button"><i class="bx ri-mail-send-line me-1"></i> Resend Email</button> ';
                            } 
                        break;
                    }

                    echo '<tr>
                            <th scope="row">'.$user['std_id'].'</th>
                            <td>'.$user['FullName'].'</td>
                            <td>'.$user['username'].'</td>
                            <td>'.$user['email'].'</td>
                            <td>'.$user['type'].'</td>
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
                <h4 class="alert-heading">Warning</h4>
                <p>This action is irreversible. All data made by this user will be deleted and will never be recovered.</p>
                <hr>
                <p class="mb-0">Do you want to delete <b><span id="full-name-placeholder"></span></b>?</p>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn" data-bs-dismiss="modal"> Yes, deregister</button>
      </div>
    </div>
  </div>
</div>
<!-- Remove Modal-->

<!-- Edit Modal -->
 <div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
      </div>
      <div class="modal-body">
         <form class="row g-3">
                <div class="col-md-12">
                  <label for="std_id" class="form-label">Student ID</label>
                  <input type="text" class="form-control" id="std_id" value="SCC-14-0001232" disabled>
                </div>

                <div class="col-md-12">
                  <label for="f_name" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="f_name" value="James, Javeluna" disabled>
                </div>

                <div class="col-md-12">
                  <label for="u_name" class="form-label">Username</label>
                  <input type="text" class="form-control" id="u_name" value="mistisakana">
                </div>

                <div class="col-md-12">
                  <label for="e_mail" class="form-label">Email</label>
                  <input type="text" class="form-control" id="e_mail" value="javelunajames255@gmail.com" disabled>
                </div>

                <div class="col-md-12">
                  <label for="u_type" class="form-label">Type</label>
                  <select class="form-select" id="u_type" aria-label="Default select example">
                <?php
                if($user_type === 'admin'){
                  echo '<option value="admin">Admin</option>
                        <option value="moderator">Moderator</option>
                        <option value="officer">Officer</option>
                        <option value="member">Member</option>
                        <option value="unverified">Unverified</option>';
                }

                if($user_type === 'moderator'){
                  echo '<option value="admin" disabled>Admin</option>
                        <option value="moderator" disabled>Moderator</option>
                        <option value="officer">Officer</option>
                        <option value="member">Member</option>
                        <option value="unverified" disabled>Unverified</option>';
                }
                ?>
                   </select>
                </div>
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="saveBtn" data-bs-dismiss="modal">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<!-- Edit Modal-->

<!-- Add Modal -->
 <div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
      </div>
      <div class="modal-body">
            <div id="response">
                
            </div>
            
            <div id="addFirstPage" class="col-md-12" style="display: block;">
                <form class="row g-3">
                    
                    <div class="col-md-12 input-group">
                      <input type="text" id="query" name="query" class="form-control" placeholder="Student ID/Name">
                      <button type="button" id="searchQuery" class="btn btn-secondary"><i class="bi bi-search"></i></button>
                    </div>

                    <div class="col-md-12">
                      <label for="f_name" class="form-label mb-25">Student ID</label>
                      <input type="text" class="form-control" id="s_id" value="ID cannot be found." disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="f_name" class="form-label mb-25">Full Name</label>
                      <input type="text" class="form-control" id="f_name" value="ID cannot be found." disabled>
                    </div>


                </form>
            </div>
            <div id="addSecondPage" class="col-md-12" style="display: none;">
                <form class="row g-3">
                    
                    <div class="col-md-12">
                      <label for="u_name" class="form-label">Username</label>
                      <input type="text" class="form-control" id="u_name" value="">
                    </div>

                    <div class="col-md-12">
                      <label for="e_mail" class="form-label">Email</label>
                      <input type="text" class="form-control" id="e_mail" value="">
                    </div>

                    <div class="col-md-12">
                      <label for="e_mail" class="form-label">Password</label>
                      <input type="password" class="form-control" id="u_pass" value="">
                    </div>

                    <div class="col-md-12">
                      <label for="u_type" class="form-label">Type</label>
                      <select class="form-select" id="u_type" aria-label="Default select example">
                    <?php
                    if($user_type === 'admin'){
                      echo '<option value="admin">Admin</option>
                            <option value="moderator">Moderator</option>
                            <option value="officer">Officer</option>
                            <option value="member">Member</option>
                            <option value="unverified">Unverified</option>';
                    }

                    if($user_type === 'moderator'){
                      echo '<option value="admin" disabled>Admin</option>
                            <option value="moderator" disabled>Moderator</option>
                            <option value="officer">Officer</option>
                            <option value="member">Member</option>
                            <option value="unverified" disabled>Unverified</option>';
                    }
                    ?>
                       </select>
                    </div>

                     
                </form>
            </div>
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">Prev Page</button>
        <button type="button" class="btn btn-success" id="nextBtn" disabled>Next Page</button>
        <button type="button" class="btn btn-success" id="submitBtn" style="display: none;">Submit</button>
      </div>
    </div>
  </div>
</div>
<!-- Add Modal-->




<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>