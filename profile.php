<?php

$page_title = "Profile";
$return_url = $_SERVER['REQUEST_URI'];
$target_id = isset($_GET['id']) ? $_GET['id'] : null;

ob_start();

session_start();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./../account/login.php?return_url=" . urlencode($return_url));
  exit();
}

$user_type = $_SESSION['user']['type'];
$user_panel = $_SESSION['user']['panel'];
$user_permission = isset(json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel']) ? json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel'] : null;

if($target_id === null){
    include 'unauthorized.php';
    exit();
}

require_once 'class/User.php';
$crud = new User();

$target_user_raw = json_decode($crud->getUser($target_id), true);

if($target_user_raw['code'] === 10000){
    $target_user_data = $target_user_raw['data'];
    $target_user_permission = json_decode($target_user_raw['data']['permission'], true)['user_permissions'];
    if(in_array('profile_private', $target_user_permission['user_panel'])){
        include 'private.php';
        exit();
    } 
    $page_title = $target_user_data['FullName']; //Override page title
} else {
    include './pages/profile-not-found.php';
    exit();
} 

$user_panel_permission = $target_user_permission['user_panel'];
$admin_panel_permission = $target_user_permission['admin_panel'];

//var_dump($target_user_permission);

$avatar = isset($target_user_data['avatar']) ? $target_user_data['avatar'] : 'default-profile.png';   

switch($target_user_data['type']){
      case 'admin':
      $user_type_display = '<span class="badge bg-danger text-white">Administrator</span>';
      break;

      case 'moderator':
      $user_type_display = '<span class="badge bg-primary text-white">Moderator</span>';
      break;

      case 'officer':
      $user_type_display = '<span class="badge bg-info text-white">Officer</span>';
      break;

      case 'member':
      $user_type_display = '<span class="badge bg-success text-white">Member</span>';
      break;

      case 'unverified':
      $user_type_display = '<span class="badge bg-secondary text-white">Unverified</span>';
      break;

      case 'banned':
      $user_type_display = '<span class="badge bg-warning text-dark">Banned</span>';
      break;
}
?>

<div class="pagetitle">
      <h1>User Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../">Home</a></li>
          <li class="breadcrumb-item active">My Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <?php echo '<img src="../assets/img/profile/'.$avatar.'" alt="Profile" class="rounded-circle">'; ?>
              <h2><?php echo $target_user_data['FullName']; ?></h2>
              <h3><?php echo $target_user_data['Course'].' '.$target_user_data['Section']; ?></h3>
              <div class="social-links mt-2">
                <?php echo $user_type_display; ?>
              </div>
            </div>
          </div>

          <div class="card">
            <ul class="list-group">
                    <li class="list-group-item"><i class="bi bi-heart-fill me-1 text-danger"></i> Hearts Received: 0</li>
                    <li class="list-group-item"><i class="ri-hand-heart-fill me-1 text-danger"></i> Hearts Given: 0</li>
                    <li class="list-group-item"><i class="bi bi-chat-text-fill me-1 text-primary"></i> Topics Started: 0</li>
                    <li class="list-group-item"><i class="bi bi-chat-left-text-fill me-1 text-primary"></i> Posts Made: 0</li>
                  </ul>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <?php 

                if($user_panel === 'admin' && in_array('user_view', $user_permission) && $user_type === 'admin' || $user_type === 'moderator' || $user_type === 'officer'){
                    echo '<li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#permission-edit">Permission</button>
                          </li>';
                }

                ?>
                

                

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">About</h5>
                  <p class="small fst-italic">No description added.</p>

                  <h5 class="card-title">Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Degree Program</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['Course']; ?></div>
                  </div>

                  <?php 
                  
                  if($target_user_data['Major'] === null){
                    echo '<div class="row">
                    <div class="col-lg-3 col-md-4 label ">Major</div>
                    <div class="col-lg-9 col-md-8">'.$target_user_data['Major'].'</div>
                  </div>';
                  }
                  
                  ?>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Section</div>
                    <div class="col-lg-9 col-md-8"><?php echo isset($target_user_data['Section']) ? $target_user_data['Section'] : 'No Section'; ?></div>
                  </div>

                  
                  <?php

                  // Assigning Organizations
                  if($target_user_data['type'] === 'officer'){
                    $user_organizations = '<span class="badge bg-warning text-dark">Supreme Student Council</span>';
                  } else {
                    $user_organizations = '<i>User not affiliated with any organization.</i>';
                  }

                  ?>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Organization(s)</div>
                    <div class="col-lg-9 col-md-8"><?php echo $user_organizations; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Created Date</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['createdDate']; ?></div>
                  </div>

                  <?php 
                    if($user_panel === 'admin' && in_array('user_view', $user_permission) && $user_type === 'admin' || $user_type === 'moderator' || $user_type === 'officer'){
                  ?>

                  <h5 class="card-title">Admin View</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['FullName']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">User ID</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['id']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Student ID</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['StudentID']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Birthdate</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['Birthday']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Gender</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['Gender']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Address</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['Address']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Civil Status</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['Status']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Semester</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['Semester']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Year Level</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['YearLevel']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">School Year</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['SchoolYear']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Username</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['username']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Email</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['email']; ?></div>
                  </div>

                  <?php
                  }
                  ?>

                </div>


                <?php 
                // Edit Profile Start
                if($user_panel === 'admin' && in_array('user_view', $user_permission) && $user_type === 'admin' || $user_type === 'moderator' || $user_type === 'officer'){
                ?>
                <div class="tab-pane fade permission-edit pt-3" id="permission-edit">
                  <!-- Profile Edit Form -->
                  <form>
                    <div class="row mb-3 mb-4">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">User Type</label>
                      <div class="col-md-8 col-lg-9">
                        <?php
                        echo '<select class="form-select" id="u_type">';
                            foreach (USER_TYPE as $value) {
                                $label = mb_convert_case($value, MB_CASE_TITLE);
                                $selected = ($target_user_data['type'] === $value) ? 'selected' : '';
                                echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                            }
                            echo '</select>';

                        ?>
                      </div>
                    </div>

                    <!-- User Panel -->
                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">General</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="col-sm-10">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if(in_array('change_password', $user_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDefault">Can change password.</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" disabled="" <?php if(in_array('change_avatar', $user_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckChecked">Can change avatar</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDisabled" <?php if(in_array('schedule_view', $user_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDisabled">Can view schedule.</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckCheckedDisabled" <?php if(in_array('debug_view', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Can view debugs.</label>
                            </div>
                        </div>
                      </div>
                    </div>
                     <!-- User Panel End -->

                    <?php 
                     if (($target_user_data['type'] === 'admin' || $target_user_data['type'] === 'moderator' || $target_user_data['type'] === 'officer') && ($user_type === 'admin' || $user_type === 'moderator')) {
                    ?>
                     <h5 class="card-title">Admin Panel</h5>

                     <!-- Admin Panel -->
                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">User</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="col-sm-10">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if(in_array('user_view', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDefault">Can access users</label>
                            </div>

                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?php if(in_array('user_add', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckChecked">Can add users</label>
                            </div>

                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDisabled" <?php if(in_array('user_query', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDisabled">Can search users</label>
                            </div>

                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDisabled" <?php if(in_array('user_edit', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDisabled">Can edit users</label>
                            </div>

                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDisabled" <?php if(in_array('user_verify', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDisabled">Can request verify users</label>
                            </div>

                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDisabled" <?php if(in_array('user_delete', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDisabled">Can delete users</label>
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">Student</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="col-sm-10">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if(in_array('student_view', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDefault">Can access students</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if(in_array('student_import', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDefault">Can import students</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?php if(in_array('student_delete', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckChecked">Can delete students</label>
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">Subject</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="col-sm-10">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if(in_array('subject_view', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDefault">Can access subjects</label>
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">Calendar</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="col-sm-10">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if(in_array('calendar_view', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDefault">Can access calendar</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if(in_array('calendar_add', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDefault">Can add event</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?php if(in_array('calendar_edit', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckChecked">Can edit event</label>
                            </div>
                             <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?php if(in_array('calendar_delete', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckChecked">Can delete event</label>
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">Support</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="col-sm-10">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if(in_array('support_view', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDefault">Can access support</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if(in_array('support_edit', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckDefault">Can edit ticket status</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?php if(in_array('support_reply', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckChecked">Can reply on tickets</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?php if(in_array('support_note_add', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckChecked">Can add notes on ticket</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?php if(in_array('support_note_delete', $admin_panel_permission)){ echo 'checked=""'; } ?>>
                              <label class="form-check-label" for="flexSwitchCheckChecked">Can delete notes on ticket</label>
                            </div>
                        </div>
                      </div>
                    </div>

                     <!-- Admin Panel End -->
                    <?php 
                     }
                    ?>
                    <hr>

                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">Action:</label>
                      <div class="col-md-8 col-lg-9">
                        <button type="button" class="btn btn-outline-danger rounded-pill"><i class="ri ri-spam-3-fill me-1"></i> Ban User</button>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-danger">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->
                </div>
                <?php 
                } // Edit Permission End
                ?>

              </div><!-- End Bordered Tabs -->

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