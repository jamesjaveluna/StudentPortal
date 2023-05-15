<?php

$page_title = "Newsfeed";
$return_url = $_SERVER['REQUEST_URI'];

ob_start();

session_start();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

$avatar = isset($_SESSION['user']['avatar']) ? $_SESSION['user']['avatar'] : 'default-profile.png';   
?>

<div class="pagetitle">
      <h1>Profile</h1>
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
              <h2><?php echo $_SESSION['user']['fname']; ?></h2>
              <h3><?php echo $_SESSION['user']['Course'].' '.$_SESSION['user']['Section']; ?></h3>
              <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
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

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Course</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['Course']; ?></div>
                  </div>

                  <?php 
                  
                  if($_SESSION['user']['Major'] === null){
                    echo '<div class="row">
                    <div class="col-lg-3 col-md-4 label ">Major</div>
                    <div class="col-lg-9 col-md-8">'.$_SESSION['user']['Major'].'</div>
                  </div>';
                  }
                  
                  ?>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Section</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['Section']; ?></div>
                  </div>


                  <?php

                  switch($_SESSION['user']['type']){
                        case 'admin':
                        $user_type_display = '<span class="badge bg-danger text-white">Admin</span>';
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

                  // Assigning Organizations
                  if($_SESSION['user']['type'] === 'officer'){
                    $user_organizations = '<span class="badge bg-warning text-dark">Supreme Student Council</span>';
                  } else {
                    $user_organizations = '<i>User not affiliated with any organization.</i>';
                  }

                  ?>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Account Type</div>
                    <div class="col-lg-9 col-md-8"><?php echo $user_type_display; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Organization(s)</div>
                    <div class="col-lg-9 col-md-8"><?php echo $user_organizations; ?></div>
                  </div>

                  <h5 class="card-title">School Records  <i class="bi bi-exclamation-circle fs-6" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Data sourced from school registrar."></i></h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Student ID</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['StudentID']; ?></div>
                  </div>
                  
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['fname']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Birthday</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['Birthday']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Gender</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['Gender']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Civil Status</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['Status']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Home address</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['Address']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Semester</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['Semester']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">School Year</div>
                    <div class="col-lg-9 col-md-8"><?php echo $_SESSION['user']['SY']; ?></div>
                  </div>

                </div>

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