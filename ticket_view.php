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
require_once 'assets/utility.php';
$crud = new Admin();
$utility = new Utility();

$target_id = $_GET['id'];

$conversation_raw = json_decode($crud->getConversation($target_id), true);

if($conversation_raw['code'] === 10000){
    $conversation_data = $conversation_raw['data'];
} else {
  include './pages/profile-not-found.php';
  exit();
} 

//var_dump($conversation_raw);

$user_permission = isset(json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel']) ? json_decode($_SESSION['user']['permission'], true)['user_permissions']['admin_panel'] : null;
$user_id = $_SESSION['user']['id'];
$user_type = $_SESSION['user']['type'];

switch($conversation_data['ticket']['status']){
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

switch($user_type){
    case 'admin':
        $user_badge = '<small><span class="badge bg-danger">Admin</span></small>';
    break;

    case 'moderator':
        $user_badge = '<small><span class="badge bg-primary">Moderator</span></small>';
    break;

    case 'officer':
        $user_badge = '<small><span class="badge bg-info">Officer</span></small>';
    break;

    default:
        $user_badge = null;
    break;
}

?>

<div class="pagetitle">
  <h1>Support Message</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../ticket">Support</a></li>
      <li class="breadcrumb-item active"><?php echo $conversation_data['ticket']['title']; ?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section ticket_view">
    <div id="response"></div>
    <div class="row">
        <!-- Start Chat Support -->
        <div class="col-lg-8">
              <div class="card">
                <div class="card-header fw-bold">
                  <div class="row m-2">
                    <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                      <?php echo '['.$conversation_data['ticket']['id'].'] '.$conversation_data['ticket']['title']; ?>
                      <small class="text-muted">
                          <?php echo $status; ?>
                      </small>
                    </li>
                  </div>
                </div>

                <div class="card-body">
                  <div id="messages" role="alert" style="height: 300px; overflow-y: scroll; overflow-x: hidden;">
                    <?php

                        foreach($conversation_data['conversations'] as $message){
                        switch($message['user_type']){
                            case 'admin':
                                $badge = '<small><span class="badge bg-danger">Admin</span></small>';
                            break;

                            case 'moderator':
                                $badge = '<small><span class="badge bg-primary">Moderator</span></small>';
                            break;

                            case 'officer':
                                $badge = '<small><span class="badge bg-info">Officer</span></small>';
                            break;

                            default:
                                $badge = null;
                            break;
                        }

                            if($message['sender_id'] !== $user_id){
                                echo '<div class="row">
                                      <div class="col-lg-8 col-sm-12">
                                        <div class="alert alert-secondary alert-dismissible fade show text-dark" role="alert">
                                          <div style="white-space: pre-wrap;">'.$message['message'].'</div>
                                          <small><p class="mb-0 text-end text-secondary">'.$message['time_ago'].'</p></small>
                                        </div>
                                      </div>
                                      <div class="col-lg-4"></div>
                                  </div>';
                            } else {
                                echo '<div class="row mt-2">
                                          <div class="col-lg-4"></div>
                                          <div class="col-lg-8 col-sm-12">
                                            <div class="alert alert-info alert-dismissible fade show text-dark" role="alert">
                                            <small><p class="mb-0 text-secondary">Replied by: <b>'.$utility->abbreviateName($message['FullName']).' '.$badge.'</b></p></small>
                                            <hr>
                                            <div style="white-space: pre-wrap;">'.$message['message'].'</div>
                                              <small><p class="mb-0 text-end text-secondary">'.$message['time_ago'].'</p></small>
                                            </div>
                                          </div>
                                      </div>';
                            }
                            
                        }

                      ?>
                  </div>
                </div>
                <div class="card-footer">  
                  <div id="ticketReply" class="input-group p-1">
                  <input id="ticket_id" type="hidden" value="<?php echo $conversation_data['ticket']['id']; ?>"/>
                  <input id="HTMLSender" type="hidden" value='Replied by: <b><?php echo $utility->abbreviateName($_SESSION['user']['fname']).' '.$user_badge; ?></b>' />
                        <textarea <?php if($conversation_data['ticket']['status'] !== 'open') { echo 'disabled'; } ?> class="form-control" placeholder="<?php if($conversation_data['ticket']['status'] !== 'open') { echo 'Check support ticket status.'; } else { echo 'Type message here'; } ?>" id="messageContent" style="height:50px;" spellcheck="false"></textarea>
                        <button <?php if($conversation_data['ticket']['status'] !== 'open') { echo 'disabled'; } ?>  type="button" id="sendMessageBtn" class="btn btn-danger btn-lg"><i class="ri-send-plane-fill me-1"></i> Send</button>
                  </div>
                </div>
              </div>
        </div>
        <!-- End Chat Support -->
        <!-- Start Quick Tools -->
        <div class="col-lg-4">
            <div class="card">
            <div class="card-body">
              <h5 class="card-title">Admin Tools</h5>
              <div class="task-container">
                  <img src="../assets/img/profile/<?php echo $conversation_data['author']['avatar']; ?>" alt="Profile" class="rounded-circle" style="width: 50px">
                
                <div class="task-info ms-4">
                  <h3 class="title"><?php echo mb_convert_case($conversation_data['author']['FullName'], MB_CASE_TITLE); ?></h3>
                  <p class="description"><?php echo $conversation_data['author']['Course'].' '.$conversation_data['author']['Section']; ?></p>
                  <p class="description"> <b><a href="../profile/<?php echo $conversation_data['author']['id']; ?>" class="text-danger">Visit Profile</a></b></p>
                </div>
            </div>

              <div class="task-container">
                <div class="task-info w-100 me-4">
                  <h3 class="title">Change Status</h3>
                      <input id="ticket_id" type="hidden" value="<? php echo $conversation_data['ticket']['id']; ?>"/>
                      <?php
                        echo '<select class="form-select mb-2 mt-2" id="u_type" aria-label="Default select example">';
                        foreach (SUPPORT_STATUS as $value) {
                            $label = mb_convert_case($value, MB_CASE_TITLE);
                            $selected = ($conversation_data['ticket']['status'] === $value) ? 'selected' : '';
                            echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                        }
                        echo '</select>';

                      ?>
                </div>
                <button id="status-saveBtn" type="button" class="btn btn-primary view-btn text-white btn-sm">Save</button>
              </div>

              <div class="task-container">
                      <div class="task-info w-100">
                        <h3 class="title mb-3">Pinned Notes</h3>
              <?php


              if($conversation_data['note'] != false){
                   foreach($conversation_data['note'] as $note){
                        switch($note['user_type']){
                            case 'admin':
                                $badge = '<small><span class="badge bg-danger">Admin</span></small>';
                            break;

                            case 'moderator':
                                $badge = '<small><span class="badge bg-primary">Moderator</span></small>';
                            break;

                            case 'officer':
                                $badge = '<small><span class="badge bg-info">Officer</span></small>';
                            break;

                            default:
                                $badge = null;
                            break;
                        }
                        echo '<div class="mb-2">
                                <p class="description mb-3">By: <b><a href="../profile/'.$note['user_id'].'" class="text-danger">'.$utility->abbreviateName($note['name']). '</a> '.$badge.'</b></p>
                                <p class="description mb-3">'.$note['message'].'</p>
                                <p class="description text-end"><small>'.$note['createdAt'].'</small></p>
                                <div class="d-grid gap-2 mt-3">';
                        if($note['created_by'] === $user_id){
                               echo '<button type="button" class="btn btn-danger text-white btn-sm remove-note" data-id="'.$note['id'].'"><i class="bi bi-eraser me-2"></i> Remove Note</button>';
                        } elseif($user_type === 'admin' || $user_type === 'moderator'){
                               echo '<button type="button" class="btn btn-danger text-white btn-sm remove-note" data-id="'.$note['id'].'"><i class="bi bi-eraser me-2"></i> Remove Note</button>';
                        }
                        echo '</div>
                              <hr>
                              </div>
                          ';
                   }
              } 

              if(in_array('support_note_add', $user_permission)){
                echo '
                      <div class="form-floating mb-3">
                        <input id="ticket_id" type="hidden" value="'.$conversation_data['ticket']['id'].'"/>
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"></textarea>
                        <label for="floatingTextarea">Leave Note</label>
                      </div>
                      <div class="d-grid gap-2 mt-3">
                        <button id="addNote" class="btn btn-primary" type="button"><i class="bx bx-pin"></i>Pin Note</button>
                      </div>
                    ';
              } else {
                    echo '
                    <div class="form-floating mb-3">
                      <textarea disabled class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 75px;"></textarea>
                      <label for="floatingTextarea">Not permitted to leave a note.</label>
                    </div>
                    <div class="d-grid gap-2 mt-3">
                      <button disabled class="btn btn-primary" type="button"><i class="bx bx-pin"></i>Pin Note</button>
                    </div>
                  ';
              }
               

              ?>
              </div>
                    </div>
              

            </div>
        </div>
        <!-- End Quick Tools -->
    </div>
</section>

<?php
$content = ob_get_contents();

ob_end_clean();

include('./template/default.php');
?>
