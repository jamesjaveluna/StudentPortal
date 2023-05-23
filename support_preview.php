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

$target_id = $_GET['id'];

$conversation_raw = json_decode($crud->getConversation($target_id), true);

if($conversation_raw['code'] === 10000){
    $conversation_data = $conversation_raw['data'];
} else {
  include './pages/profile-not-found.php';
  exit();
} 

$user_id = $_SESSION['user']['id'];

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

?>

<div class="pagetitle">
  <h1>Support Message</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../support">Support</a></li>
      <li class="breadcrumb-item active"><?php echo $conversation_data['ticket']['title']; ?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section support">
    <div class="row">
        <div class="col-lg-12">
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
                <div class="input-group p-1">
                        <input id="ticket_id" type="hidden" value="<?php echo $conversation_data['ticket']['id']; ?>"/>
                        <textarea <?php if($conversation_data['ticket']['status'] !== 'open' && $conversation_data['ticket']['status'] !== 'pending') { echo 'disabled'; } ?> class="form-control" placeholder="<?php if($conversation_data['ticket']['status'] !== 'open' && $conversation_data['ticket']['status'] !== 'pending') { echo 'Unable to send message.'; } else { echo 'Type message here'; } ?>" id="messageContent" style="height:50px;" spellcheck="false"></textarea>
                        <button <?php if($conversation_data['ticket']['status'] !== 'open' && $conversation_data['ticket']['status'] !== 'pending') { echo 'disabled'; } ?> type="button" id="sendMessageBtn" class="btn btn-danger btn-lg"><i class="ri-send-plane-fill me-1"></i> Send</button>

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
