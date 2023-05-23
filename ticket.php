<?php

$page_title = "Support";
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
$support_raw = json_decode($crud->getTickets(), true);

if($support_raw['code'] === 10000){
    $support_data = $support_raw['data'];
} 

?>

 <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Support</h5>

              <?php

              if($support_raw['code'] === 10000){
                  echo '<div class="col-lg-12 text-end mb-3">
                    <button type="button" id="addBtnExecuter" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addReport"><i class="bi bi-plus-lg me-1"></i> New</button>
                </div>';

                  echo '<hr>';
              }

              ?>

              <div class="list-group list-group-flush">
                <?php

                if($support_raw['code'] === 10000){
                    foreach($support_data as $ticket){
                        switch($ticket['status']){
                            case 'open':
                                $status = '<span class="badge bg-primary">OPEN</span>';
                            break;

                            case 'solved':
                                $status = '<span class="badge bg-success">SOLVED</span>';
                            break;

                            case 'closed':
                                $status = '<span class="badge bg-danger">CLOSED</span>';
                            break;

                            case 'pending':
                                $status = '<span class="badge bg-warning">PENDING</span>';
                            break;
                        }


                        echo '<a href="ticket/'.$ticket['id'].'" class="list-group-item list-group-item-action " aria-current="true">
                               <div class="d-flex w-100 justify-content-between">
                                 <h5 class="mb-1 fw-bold">[#'.$ticket['id'].'] '.$ticket['title'].'</h5>
                                 <small class="text-muted">'.$status.'</small>
                               </div>
                               <p class="mb-1">'.$ticket['latest_message'].'</p>
                               <small>'.$ticket['time_ago'].'</small>
                             </a>';
                    }
                } elseif($support_raw['code'] === 10002){
                      if(GEN_DEBUG === true && $user_type === 'admin' && in_array('debug_view', $user_permission)){
                        
                        echo '<tr><td class="datatable-empty" colspan="6">
                                <div class="alert alert-danger fade show" role="alert">
                                  <h4 class="alert-heading">[DEBUG IS ENABLED]</h4>
                                  <p><i class="bi bi-exclamation-circle me-1"></i> Only admin with a <code>debug_view</code> permission can view this error. You can also disable GEN_DEBUG in Portal Settings. </p>
                                  <hr>
                                  <p class="mb-4">'.$support_raw['debug'].'</p>
                                  <center><button type="button" class="btn btn-danger mb-3">Request Permission</button></center>
                                </div></td></tr>';
                                echo '<tr><td class="datatable-empty" colspan="6">
                        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                            <center>
                                <h2>'.$support_raw['message'].'</h2>
                            </center>

                            <img src="../assets/img/svg/no-record.svg" class="img-fluid py-5" alt="Page Not Found">
                          </section>
                        </td></tr>';
                      } else {
                        echo '<div class="alert alert-danger fade show text-center" role="alert">
                             <h4 class="alert-heading">[DEBUG IS ENABLED]</h4>
                             <p><i class="bi bi-exclamation-circle me-1"></i> Only users with a <code>debug_view</code> permission can view this error. You can also disable GEN_DEBUG in Portal Settings. </p>
                             <hr>
                             <p class="mb-4">'.$support_raw['debug'].'</p>
                             <button type="button" class="btn btn-danger mb-3">Request Permission</button>
                           </div>';
                      }
                } else {
                    echo '<section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                            <center>
                                <h2>No users created a ticket yet.</h2>
                            </center>

                            <img src="../assets/img/svg/no-message.svg" class="img-fluid py-5" alt="Page Not Found">
                          </section>';

                }
                ?>

              </div>
              
            </div>
          </div>

        </div>
      </div>
 </section>

 <!-- Add Modal -->
 <div class="modal fade" id="addReport" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Ticket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
      </div>
      <div class="modal-body">
            <div id="response"></div>
            
                <form id="createTicketForm" class="row g-3">

                    <div class="col-sm-12">
                        <label for="issueType" class="form-label mb-25">Student ID</label>
                        <select class="form-select" id="issueType" aria-label="Default select example">
                          <option value="account">Account Issues</option>
                          <option value="schedule">Schedule Issues</option>
                          <option value="calendar">Calendar Issues</option>
                          <option value="suggestion">Suggestion</option>
                          <option value="others" selected="">Others</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                      <label for="r_title" class="form-label mb-25">Title</label>
                      <input type="text" class="form-control" id="r_title" placeholder="Enter a brief summary of your issue">
                    </div>

                    <div class="col-md-12">
                      <label for="r_message" class="form-label mb-25">Message</label>
                      <textarea class="form-control" placeholder="Provide details about your issue or question..." id="r_message" style="height: 100px;" spellcheck="false"></textarea>
                    </div>

                </form>
         
      </div>
     <div class="modal-footer">
       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
       <button type="button" class="btn btn-success" id="createTicketBtn">Create</button>
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