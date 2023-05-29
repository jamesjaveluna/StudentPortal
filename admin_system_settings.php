<?php

$page_title = "System Settings";
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

if($user_panel !== 'admin'){
    include 'unauthorized.php';
    exit();
}

$users_raw = json_decode($crud->getUsers(), true);

if($users_raw['code'] === 10000){
    $users_data = $users_raw['data'];
}

?>

 <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">System Settings</h5>

              <!-- Default Tabs -->
              <ul class="nav nav-tabs" id="myTabjustified" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="gen-tab" data-bs-toggle="tab" data-bs-target="#gen-justified" type="button" role="tab" aria-controls="home" aria-selected="true">General</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="mailer-tab" data-bs-toggle="tab" data-bs-target="#mailer-justified" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Mailer</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="recaptcha-tab" data-bs-toggle="tab" data-bs-target="#recaptcha-justified" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Recaptcha</button>
                </li>
              </ul>
              <div class="tab-content pt-4" id="myTabjustifiedContent">
                <div class="tab-pane fade show active" id="gen-justified" role="tabpanel" aria-labelledby="gen-tab">
                    
                    <h5 class="card-title">Support System</h5>
                
                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Maintenance</label>
                      <div class="col-sm-10">
                        <div class="form-check form-switch mt-2">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">
                          <label class="form-check-label" for="flexSwitchCheckChecked">Disable access to all users in the system.</label>
                        </div>
                      </div>
                  </div>  

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Schedule</label>
                      <div class="col-sm-10">
                        <div class="form-check form-switch mt-2">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                          <label class="form-check-label" for="flexSwitchCheckChecked">Set Schedule System to maintenance.</label>
                        </div>
                      </div>
                  </div>  

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Support</label>
                      <div class="col-sm-10">
                        <div class="form-check form-switch mt-2">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                          <label class="form-check-label" for="flexSwitchCheckChecked">Set Support System to maintenance.</label>
                        </div>
                      </div>
                  </div>  

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Enable Debug</label>
                      <div class="col-sm-10">
                        <div class="form-check form-switch mt-2">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">
                          <label class="form-check-label" for="flexSwitchCheckChecked">Users with <code>debug_view</code> permission can view it.</label>
                        </div>
                      </div>
                  </div>

                  <h5 class="card-title">Site Configuration</h5>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Site Name</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="Cecilian Student Portal">
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Site URL</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="https://proudcecilian.online">
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Site Author</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="James Javeluna">
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Site Icon</label>
                      <div class="col-md-10">
                        <img src="./.././../../assets/img/SCO.png" style="width: auto;height: 150px;object-fit: cover;" class="card-img-top" alt="...">
                        <div class="pt-2">
                          <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                  </div>

                  <h5 class="card-title">Support System</h5>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Message Cooldown</label>
                      <div class="col-sm-10">
                        <label for="customRange2" class="form-label">Value: 3 (Minutes)   <small class="text-info">Set 0 for unlimited. (Not recommended)</small></label>
                        <input type="range" class="form-range" min="0" max="5" step="0.5" id="customRange2">
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Support Status</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEmail" value="open,closed,solved,pending">
                      </div>
                  </div>

                  <h5 class="card-title">API System</h5>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Secret Key</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="my-secret-key">
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Duration</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="2592000">
                      </div>
                  </div>

                  <div class="text-center">
                      <button type="submit" class="btn btn-danger">Save Changes</button>
                  </div>
                </div>
                <div class="tab-pane fade" id="mailer-justified" role="tabpanel" aria-labelledby="mailer-tab">
                  
                  <div id="response"></div>

                  <form class="needs-validation" novalidate>
                     <div class="row mb-3">
                     <label for="inputEmail3" class="col-sm-2 col-form-label">Mailer</label>
                         <div class="col-sm-10">
                           <div class="form-check form-switch mt-2">
                             <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" disabled="">
                             <label class="form-check-label" for="flexSwitchCheckChecked">Enable Mailer System</label>
                           </div>
                         </div>
                     </div>  

                     <div class="row mb-3 position-relative">
                         <label for="inputEmail3" class="col-sm-2 col-form-label">Host</label>
                         <div class="col-sm-10">
                           <input type="text" class="form-control" id="inputOldPassword" value="smtp.hostinger.com">
                           <div class="invalid-feedback"></div>
                         </div>
                     </div>

                     <div class="row mb-3 position-relative">
                         <label for="inputEmail3" class="col-sm-2 col-form-label">Port</label>
                         <div class="col-sm-10">
                           <input type="text" class="form-control" id="inputOldPassword" value="465">
                           <div class="invalid-feedback"></div>
                         </div>
                     </div>

                     <div class="row mb-3 position-relative">
                         <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                         <div class="col-sm-10">
                           <input type="text" class="form-control" id="inputOldPassword" value="no-reply@proudcecilian.online">
                           <div class="invalid-feedback"></div>
                         </div>
                     </div>

                     <div class="row mb-3 position-relative">
                         <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                         <div class="col-sm-10">
                           <input type="password" class="form-control" id="inputOldPassword" value="P@ssword123">
                           <div class="invalid-feedback"></div>
                         </div>
                     </div>

                     <div class="row mb-3 position-relative">
                         <label for="inputEmail3" class="col-sm-2 col-form-label">From</label>
                         <div class="col-sm-10">
                           <input type="text" class="form-control" id="inputOldPassword" value="no-reply@proudcecilian.online">
                           <div class="invalid-feedback"></div>
                         </div>
                     </div>

                     <div class="row mb-3 position-relative">
                         <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                         <div class="col-sm-10">
                           <input type="text" class="form-control" id="inputOldPassword" value="Student Portal">
                           <div class="invalid-feedback"></div>
                         </div>
                     </div>

                     <div class="row mb-3 position-relative">
                         <label for="inputEmail3" class="col-sm-2 col-form-label">Secure</label>
                         <div class="col-sm-10">
                           <input type="text" class="form-control" id="inputOldPassword" value="ssl">
                           <div class="invalid-feedback"></div>
                         </div>
                     </div>

                     <div class="row mb-3">
                         <label for="inputEmail3" class="col-sm-2 col-form-label">Auth</label>
                         <div class="col-sm-10">
                           <div class="form-check form-switch mt-2">
                             <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">
                             <label class="form-check-label" for="flexSwitchCheckChecked">Disable access to all users in the system.</label>
                           </div>
                         </div>
                     </div>  

                     <div class="row mb-3">
                         <label for="inputEmail3" class="col-sm-2 col-form-label">Debug</label>
                         <div class="col-sm-10">
                           <div class="form-check form-switch mt-2">
                             <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">
                             <label class="form-check-label" for="flexSwitchCheckChecked">Disable access to all users in the system.</label>
                           </div>
                         </div>
                     </div>  

                     <div class="text-center">
                         <button type="submit" class="btn btn-danger" id="saveChanges">Save Changes</button>
                     </div>
                  </form>
                  
                </div>
                <div class="tab-pane fade" id="recaptcha-justified" role="tabpanel" aria-labelledby="recaptcha-tab">
                  <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Recaptcha</label>
                      <div class="col-sm-10">
                        <div class="form-check form-switch mt-2">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" disabled="">
                          <label class="form-check-label" for="flexSwitchCheckChecked">Enable Recaptcha System</label>
                        </div>
                      </div>
                  </div>  

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Site Key</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="6Ld9ILglAAAAAPLbeclOEH61bvkBxMYymGkjAR04">
                      </div>
                  </div>

                  <div class="row mb-3">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Secret Key</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="6Ld9ILglAAAAAFUKePvnO8m6hYWIxQQ7XlcxZOnA">
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