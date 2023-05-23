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
      <li class="breadcrumb-item active">Users</li>
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

              if($user_type === 'admin' && in_array('user_add', $user_permission)){
                   echo '<div class="col-lg-12 text-end mb-3">
                              <button type="button" id="addBtnExecuter" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg me-1"></i> New</button>
                          </div>';
              }

              ?>
              <!-- End Buttons -->


              <!-- Table with stripped rows -->
                  
                  <?php 
                  

                  if($users_raw['code'] === 10000){

                    echo '<table id="userTable" class="table datatable">
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
                        <tbody>';

                    foreach($users_data as $user){

                      switch($user_type){
                          
                          case 'admin': // Can Edit, Resend Verification, Deregister
                              if(in_array('user_edit', $user_permission)){
                                $buttons = '<button class="btn btn-primary editBtn" data-id="'.$user['id'].'" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bx ri-edit-box-line"></i></button> ';
                              } else {
                                $buttons = '';
                              }

                              // For resend verification button
                              if($user['type'] === 'unverified' && in_array('user_verify', $user_permission)){
                                  $buttons .= '<button class="btn btn-warning resendEmail" data-id="'.$user['id'].'" type="button"><i class="bx ri-mail-send-line me-1"></i> Verify</button> ';
                              } 

                              if(in_array('user_delete', $user_permission)){
                                   $buttons .= '<button class="btn btn-danger deregisterBtn" data-id="'.$user['id'].'" data-name="'.$user['FullName'].'" type="button" data-bs-toggle="modal" data-bs-target="#confirmationModal"><i class="bi bi-eraser"></i></button> ';
                              }
                          break;

                          // 
                          case 'moderator': // Can Edit, Resend Verification
                              if(in_array('user_edit', $user_permission)){
                                $buttons = '<button class="btn btn-primary editBtn" data-id="'.$user['id'].'" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bx ri-edit-box-line me-1"></i></button> ';
                              } else {
                                $buttons = '';
                              }

                              // For resend verification button
                              if($user['type'] === 'unverified' && in_array('user_verify', $user_permission)){
                                  $buttons .= '<button class="btn btn-warning resendEmail" data-id="'.$user['id'].'" type="button"><i class="bx ri-mail-send-line me-1"></i> Verify</button> ';
                              } 
                              
                          break;

                          case 'officer': // Can Resend Verification
                              $buttons = '';

                              // For resend verification button
                              if($user['type'] === 'unverified' && in_array('user_verify', $user_permission)){
                                  $buttons = '<button class="btn btn-warning resendEmail" data-id="'.$user['id'].'" type="button"><i class="bx ri-mail-send-line me-1"></i> Verify</button> ';
                              } else {
                                  $buttons = '<span class="badge rounded-pill bg-secondary">No action</span>';
                              }
                          break;
                      }

                      echo '<tr>
                                <td>'.$user['std_id'].'</td>
                                <td><a href="./profile/'.$user['id'].'" class="text-danger">'.$user['FullName'].'</a></td>
                                <td>'.$user['username'].'</td>
                                <td>'.$user['email'].'</td>
                                <td>'.$user['type'].'</td>
                                <td>'.$buttons.'</td>
                            </tr>
                            ';
                    }

                    echo '
                </tbody>
              </table>';

                  } else {
                        if(GEN_DEBUG === true && in_array('debug_view', $user_permission)){
                            //echo '<tr><td class="datatable-empty" colspan="6">'.$users_raw['message'].'</td></tr>';
                            echo '<td class="datatable-empty" colspan="6">
                                    <div class="alert alert-danger fade show text-center" role="alert">
                                      <h4 class="alert-heading">[DEBUG IS ENABLED]</h4>
                                      <p><i class="bi bi-exclamation-circle me-1"></i> Only users with a <code>debug_view</code> permission can view this error. You can also disable GEN_DEBUG in Portal Settings. </p>
                                      <hr>
                                      <p class="mb-4">'.$users_raw['debug'].'</p>
                                      <button type="button" class="btn btn-danger mb-3">Request Permission</button>
                                    </div></td>';
                       } else {
                           //echo '<tr><td class="datatable-empty" colspan="6">'.$users_raw['message'].'</td></tr>';
                       }
                  }
                  
                  
                  ?>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
 </section>

<?php
    if(in_array('user_delete', $user_permission)) {
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
<?php
 }

 if(in_array('user_edit', $user_permission)) {
?>
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
                  <input type="text" class="form-control" id="std_id" value="" disabled>
                </div>

                <div class="col-md-12">
                  <label for="f_name" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="f_name" value="" disabled>
                </div>

                <div class="col-md-12">
                  <label for="u_name" class="form-label">Username</label>
                  <input type="text" class="form-control" id="u_name" value="">
                </div>

                <div class="col-md-12">
                  <label for="e_mail" class="form-label">Email</label>
                  <?php
                    if($user_type === 'admin'){
                        echo '<input type="text" class="form-control" id="e_mail" value="">';
                    } else {
                        echo '<input type="text" class="form-control" id="e_mail" value="" disabled>';
                    }
                  ?>
                </div>

                <div class="col-md-12">
                  <label for="u_type" class="form-label">Type</label>
                  <select class="form-select" id="u_type">
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
<?php
 }

 if(in_array('user_add', $user_permission)) {
?>
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
 }
?>
<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>