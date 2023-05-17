<?php

$page_title = "Calendar of Activity";
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

$users_raw = json_decode($crud->getUsers(), true);

if($users_raw['code'] === 10000){
    $users_data = $users_raw['data'];
} else {
    include './pages/calendar-service-error.php';
    exit();
}

?>
<div class="pagetitle">
  <h1>Calendar of Activity</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Admin Panel</li>
      <li class="breadcrumb-item active">Calendar of Activity</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

 <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>

              <!-- Responses -->
              <div id="response"></div>
              <!-- End Responses -->

              <!-- Calendar -->
                <div id='calendar'></div>
              <!-- End Calendar -->

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