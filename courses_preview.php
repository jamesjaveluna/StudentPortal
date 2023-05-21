<?php

$page_title = "Courses Preview";
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

require_once 'class/Admin.php';
$crud = new Admin();

$target_subject_raw = json_decode($crud->getCoursesByID($target_id), true);

if($target_subject_raw['code'] === 10000){
    $target_subject_data = $target_subject_raw['data'];
    $page_title = $target_subject_data['code']; //Override page title
} else {
    include './pages/profile-not-found.php';
    exit();
} 

    //var_dump($target_subject_data);
?>

<div class="pagetitle">
      <h1>Course Details</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../courses">Courses</a></li>
          <li class="breadcrumb-item active">Course Details</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <?php echo '<img src="../assets/img/SCC.png" alt="Profile" class="rounded-circle">'; ?>
              <h2><?php echo $target_subject_data['code']; ?></h2>
              <h3><?php echo $target_subject_data['description']; ?></h3>
              <div class="social-links mt-2">
                <?php// echo $user_type_display; ?>
              </div>
            </div>
          </div>

          <div class="card">
            <ul class="list-group">
                    <li class="list-group-item"><i class="ri-user-shared-fill me-1 text-danger"></i> Students Enrolled: 0</li>
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

                //if($target_id === $_SESSION['user']['id']){
                //    echo '<li class="nav-item">
                //            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                //          </li>';
                //}

                ?>
                

                

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">About</h5>
                  <p class="small fst-italic">No description added.</p>

                  <h5 class="card-title">Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Instructor Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_subject_data['instructor_name']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Room Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_subject_data['room_name']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Class Days</div>
                    <div class="col-lg-9 col-md-8"><?php echo isset($target_subject_data['AI_days']) ? $target_subject_data['AI_days'] : 'Wrong Format'; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Class Time</div>
                    <div class="col-lg-9 col-md-8"><?php echo isset($target_subject_data['AI_civilian_time']) ? $target_subject_data['AI_civilian_time'] : 'Wrong Format'; ?></div>
                  </div>

                   <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Updated Date</div>
                    <div class="col-lg-9 col-md-8"><?php echo $target_subject_data['updatedDate']; ?></div>
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