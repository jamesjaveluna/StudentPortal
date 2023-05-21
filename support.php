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

require_once 'class/Support.php';
$crud = new Support();

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
                            case 'Open':
                                $status = '<span class="badge bg-primary">OPEN</span>';
                            break;

                            case 'Solved':
                                $status = '<span class="badge bg-success">SOLVED</span>';
                            break;

                            case 'Closed':
                                $status = '<span class="badge bg-danger">CLOSED</span>';
                            break;
                        }


                        echo '<a href="support/'.$ticket['id'].'" class="list-group-item list-group-item-action " aria-current="true">
                               <div class="d-flex w-100 justify-content-between">
                                 <h5 class="mb-1 fw-bold">[#'.$ticket['id'].'] '.$ticket['title'].'</h5>
                                 <small class="text-muted">'.$status.'</small>
                               </div>
                               <p class="mb-1">'.$ticket['latest_message'].'</p>
                               <small>'.$ticket['time_ago'].'</small>
                             </a>';
                    }
                } else {
                    echo '<section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                            <center>
                                <h2>No support tickets created yet.</h2>
                            </center>

                            <button type="button" id="addBtnExecuter" class="btn" data-bs-toggle="modal" data-bs-target="#addReport"> Create Ticket</button>
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