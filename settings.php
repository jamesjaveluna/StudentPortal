<?php

$page_title = "Account Settings";
$return_url = $_SERVER['REQUEST_URI'];

ob_start();

session_start();


// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

?>

 <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Account Settings</h5>

              <!-- Default Tabs -->
              <ul class="nav nav-tabs" id="myTabjustified" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-justified" type="button" role="tab" aria-controls="home" aria-selected="true">General</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-justified" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Privacy</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-justified" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Notification</button>
                </li>
              </ul>
              <div class="tab-content pt-4" id="myTabjustifiedContent">
                <div class="tab-pane fade show active" id="home-justified" role="tabpanel" aria-labelledby="home-tab">
                  
                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Student ID</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputText" value="<?php echo $_SESSION['user']['std_id']; ?>" disabled>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Full Name</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputText" value="<?php echo $_SESSION['user']['fname']; ?>" disabled>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputText" value="<?php echo $_SESSION['user']['email']; ?>" disabled>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Organizations <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Organization includes Supreme Student Council or Department Council"></i></label>
                      <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary" disabled>Join an Organization</button>
                      </div>
                  </div>

                  <div class="text-center">
                      <button type="submit" class="btn btn-danger">Save Changes</button>
                  </div>
                </div>
                <div class="tab-pane fade" id="profile-justified" role="tabpanel" aria-labelledby="profile-tab">
                  
                  <div class="row mb-3">
                      <label class="col-sm-2 col-form-label">Profile Visibility</label>
                      <div class="col-sm-10">
                        <select class="form-select" aria-label="Profile Visibility" disabled>
                          <option selected="">Public</option>
                          <option value="1">Private</option>
                        </select>
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Enable 2FA <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Two-Factor Authentication"></i></label>
                      <div class="col-sm-10">
                        <button type="submit" class="btn btn-success" disabled>2FA Disabled</button>
                      </div>
                  </div>

                  <div class="text-center">
                      <button type="submit" class="btn btn-danger">Save Changes</button>
                  </div>

                </div>
                <div class="tab-pane fade" id="contact-justified" role="tabpanel" aria-labelledby="contact-tab">
                  <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="changesMade" checked="">
                          <label class="form-check-label" for="changesMade">
                            Updates with my requests
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="newProducts" checked="">
                          <label class="form-check-label" for="newProducts">
                            News & Updates of Cecilian Portal
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="proOffers">
                          <label class="form-check-label" for="proOffers">
                            Organization/clubs invitations
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="securityNotify" checked="" disabled="">
                          <label class="form-check-label" for="securityNotify">
                            Security alerts
                          </label>
                        </div>
                      </div>
                  </div>
                  <div class="text-center">
                      <button type="submit" class="btn btn-danger">Save Changes</button>
                  </div>
                </div>
              </div><!-- End Default Tabs -->

            </div>
          </div>

        </div>
      </div>
 </section>

<!-- Modal for adding/editing events -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div id="eventModalSize" class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="response"></div>
        <form id="eventForm" class="row">
            <div id="simpleOption" class="col-lg-12" style="display: block">
                <div class="row">
                  <div class="col-6">
                    <div class="mb-3">
                      <label for="startDateInput" class="form-label">Title</label>
                      <input type="text" class="form-control" id="titleInput" name="title" required>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="mb-3">
                      <label for="startTimeInput" class="form-label">Location</label>
                      <input type="text" class="form-control" id="locationInput" name="title" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="mb-3">
                      <label for="startDateInput" class="form-label">Start Date</label>
                      <input type="date" class="form-control" id="startDateInput" name="start_date" required>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="mb-3">
                      <label for="endDateInput" class="form-label">End Date</label>
                      <input type="date" class="form-control" id="endDateInput" name="end_date" required>
                    </div>
                  </div>
                </div>

                <div id="endDateForm" class="row" style="display: none">
                <div class="col-6">
                    <div class="mb-3">
                      <label for="startTimeInput" class="form-label">Start Time</label>
                      <input type="time" class="form-control" id="startTimeInput" name="start_time" required>
                    </div>
                  </div>
                  
                  <div class="col-6">
                    <div class="mb-3">
                      <label for="endTimeInput" class="form-label">End Time</label>
                      <input type="time" class="form-control" id="endTimeInput" name="end_time" required>
                    </div>
                  </div>
                </div>

                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="allDayInput" name="allDay" checked="">
                  <label class="form-check-label" for="allDayInput">All-day event</label>
                </div>

                <div class="mb-3 form-check mb-2">
                  <input type="checkbox" class="form-check-input" id="noClassInput" name="noClass">
                  <label class="form-check-label" for="noClassInput">No Class</label>
                </div>

                <div class="col-lg-12 text-end">
                        <a id="advancedSettings" href="javascript:void(0);" data-open="0">Advanced Settings</a>
                </div>
            </div>
            <div id="advancedOption" class="col-lg-6" style="display: none">
                <div class="mb-3">
                  <label for="titleInput" class="form-label">Can view</b></label>
                  <div class="col-sm-10">

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="bsitInput" checked>
                      <label class="form-check-label" for="gridCheck1">
                        <b>BSIT</b> - Bachelor of Science in Information & Technology
                      </label>
                    </div>

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="bsbaInput" checked>
                      <label class="form-check-label" for="gridCheck1">
                        <b>BSBA</b> - Bachelor of Science in Business Administration 
                      </label>
                    </div>

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="bsedInput" checked>
                      <label class="form-check-label" for="gridCheck1">
                        <b>BSED</b> - Bachelor of Secondary Education
                      </label>
                    </div>

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="beedInput" checked>
                      <label class="form-check-label" for="gridCheck1">
                        <b>BEED</b> - Bachelor of Elementary Education
                      </label>
                    </div>

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="bshmInput" checked>
                      <label class="form-check-label" for="gridCheck1">
                        <b>BSHM</b> - Bachelor of Science in Hospitality Management
                      </label>
                    </div>

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="bstmInput" checked>
                      <label class="form-check-label" for="gridCheck1">
                        <b>BSTM</b> - Bachelor of Science in Tourist Management
                      </label>
                    </div>

                    <div class="form-check mb-3">
                      <input class="form-check-input" type="checkbox" id="bscrimInput" checked>
                      <label class="form-check-label" for="gridCheck1">
                        <b>BSCRIM</b> - Bachelor of Science in Criminology
                      </label>
                    </div>

                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">Permission</th>
                          <th scope="col">Moderator</th>
                          <th scope="col">Teacher</th>
                          <th scope="col">Officer</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Can edit</td>
                          <td><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="modEdit" checked="" <?php if($user_type !== 'admin' || $user_type !== 'moderator') echo 'disabled'; ?>>
                                <label class="form-check-label" for="gridCheck1">
                                  Allowed
                                </label>
                              </div>
                          </td>
                          <td><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="teachEdit" <?php if($user_type !== 'admin' || $user_type !== 'moderator') echo 'disabled'; ?>>
                                <label class="form-check-label" for="gridCheck1">
                                  Allowed
                                </label>
                              </div>
                          </td>
                          <td><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="officerEdit" <?php if($user_type !== 'admin' || $user_type !== 'moderator') echo 'disabled'; ?>>
                                <label class="form-check-label" for="gridCheck1">
                                  Allowed
                                </label>
                              </div>
                          </td>
                        </tr>
                        <tr>
                          <td>Can delete</td>
                          <td><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="modDelete" <?php if($user_type !== 'admin' || $user_type !== 'moderator') echo 'disabled'; ?>>
                                <label class="form-check-label" for="gridCheck1">
                                  Allowed
                                </label>
                              </div>
                          </td>
                          <td><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="teachDelete" <?php if($user_type !== 'admin' || $user_type !== 'moderator') echo 'disabled'; ?>>
                                <label class="form-check-label" for="gridCheck1">
                                  Allowed
                                </label>
                              </div>
                          </td>
                          <td><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="officerDelete" <?php if($user_type !== 'admin' || $user_type !== 'moderator') echo 'disabled'; ?>>
                                <label class="form-check-label" for="gridCheck1">
                                  Allowed
                                </label>
                              </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="addEventBtn">Add</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal for adding/editing events -->


 

<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>