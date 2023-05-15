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
} else {
    include 'profile-not-found.php';
    exit();
} 

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

                if($target_id === $_SESSION['user']['id']){
                    echo '<li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                          </li>';

                    echo '<li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
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
                    <div class="col-lg-3 col-md-4 label ">Course</div>
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
                    <div class="col-lg-9 col-md-8"><?php echo $target_user_data['Section']; ?></div>
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

                </div>


                <?php 
                // Edit Profile Start
                if($target_id === $_SESSION['user']['id']){ 
                ?>
                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                  <!-- Profile Edit Form -->
                  <form>
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <?php echo '<img src="../assets/img/profile/'.$avatar.'" alt="Profile">'; ?>
                        <div class="pt-2">
                          <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                      <div class="col-md-8 col-lg-9">
                        <?php echo '<input name="username" type="text" class="form-control" id="username" value="'.$_SESSION['user']['username'].'" disabled>'; ?>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <?php echo '<input name="username" type="text" class="form-control" id="username" value="'.$_SESSION['user']['email'].'" disabled>'; ?>
                      </div>
                    </div>
                    
                    <hr>

                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label">Request:</label>
                      <div class="col-md-8 col-lg-9 d-grid">
                        <button type="button" class="btn btn-outline-danger rounded-pill"><i class="bx bx-mail-send me-1"></i> Change Password</button>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label"></label>
                      <div class="col-md-8 col-lg-9 d-grid">
                        <button type="button" class="btn btn-outline-danger rounded-pill"><i class="bx bxs-calendar-edit me-1"></i> Update Schedule</button>
                      </div>
                    </div>

                    <div class="row mb-5">
                      <label for="username" class="col-md-4 col-lg-3 col-form-label"></label>
                      <div class="col-md-8 col-lg-9 d-grid">
                        <button type="button" class="btn btn-outline-danger rounded-pill"><i class="bx bxs-edit me-1"></i> Update Details</button>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-danger">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->
                </div>
                <?php 
                } // Edit Profile end
                ?>

                <?php 

                // Profile Settings start
                if($target_id === $_SESSION['user']['id']){
                    
                ?>
                <div class="tab-pane fade pt-3" id="profile-settings">

                  <!-- Settings Form -->
                  <form>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="changesMade" checked>
                          <label class="form-check-label" for="changesMade">
                            Changes made to your account
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="newProducts" checked>
                          <label class="form-check-label" for="newProducts">
                            Information on new products and services
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="proOffers">
                          <label class="form-check-label" for="proOffers">
                            Marketing and promo offers
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                          <label class="form-check-label" for="securityNotify">
                            Security alerts
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-danger">Save Changes</button>
                    </div>
                  </form><!-- End settings Form -->

                </div>
                <?php 
                }   // Profile Settings end
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