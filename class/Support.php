<?php

require_once 'config/jwt.php';
require_once 'config/config.php';
require_once 'Database.php';
require_once 'Mailer.php';
require_once __DIR__.'./../assets/utility.php';


class Support {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function getTickets() {
    $allowedUserType = array('admin', 'moderator', 'officer', 'member');
    $allowedPermission = null;
    $section = 'bypass';

    $utility = new Utility();
    switch($utility->checkPermission($allowedUserType, $section, $allowedPermission)){
        case 10001:
            http_response_code(401);
            return json_encode(array(
                'message' => 'Session already expired.',
                'code' => 10001
            ));
            break;

        case 10002:
            http_response_code(401);
            return json_encode(array(
                'message' => 'Unauthorized access',
                'code' => 10002,
                'debug' => 'Permission required: '.$allowedPermission
            ));
            break;
    }

    $user_id = $_SESSION['user']['id'];

    try {
        $stmt = $this->conn->prepare("
            SELECT ST.*,
                SC.message AS latest_message,
                SC.createdAt AS messageCreatedAt
            FROM SupportTicket ST
            LEFT JOIN (
                SELECT ticket_id, message, createdAt
                FROM SupportConversation SC
                WHERE (ticket_id, createdAt) IN (
                    SELECT ticket_id, MAX(createdAt)
                    FROM SupportConversation
                    GROUP BY ticket_id
                )
            ) AS SC ON SC.ticket_id = ST.id
            WHERE ST.sender_id = :sender_id ORDER BY ST.updatedAt DESC;
        ");
        $stmt->execute(array(':sender_id' => $user_id));
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$tickets) {
            http_response_code(401);
            return json_encode(array(
                'message' => 'No tickets yet.',
                'code' => 10003
            ));
        } else {
            // Calculate time ago for each ticket
            $data = array();
            foreach ($tickets as $ticket) {
                $createdAt = $ticket['messageCreatedAt'];
                $timeAgo = $utility->getAgo($createdAt); // Call the function to calculate time ago
                $ticket['time_ago'] = $timeAgo;
                $data[] = $ticket;
            }

            return json_encode(array(
                'message' => 'Tickets fetched successfully.',
                'code' => 10000,
                'data' => $data
            ));
        }

        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch Users Data.',
            'code' => $tickets
        ));

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        exit();
    }
 }

  public function createTicket() {
   $allowedUserType = array('admin', 'moderator', 'officer', 'member');
   $allowedPermission = null;
   $section = 'bypass';
   
   $utility = new Utility();
   switch($utility->checkPermission($allowedUserType, $section, $allowedPermission)){
       case 10001:
           http_response_code(401);
           return json_encode(array(
               'message' => 'Session already expired.',
               'code' => 10001
           ));
           break;
   
       case 10002:
           http_response_code(401);
           return json_encode(array(
               'message' => 'Unauthorized access',
               'code' => 10002,
               'debug' => 'Permission required: '.$allowedPermission
           ));
           break;
   }
   
   // Check if required parameters are provided
   if (!isset($_POST['issue_type']) || !isset($_POST['title']) || !isset($_POST['message'])) {
       http_response_code(400);
       return json_encode(array(
           'message' => 'Missing required parameters'
       ));
   }
   
   // Fetch POST data
   $issueType = htmlspecialchars($_POST['issue_type'], ENT_QUOTES, 'UTF-8');
   $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
   $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
   $user_id = $_SESSION['user']['id'];
   
   // Check if any of the variables are empty
   if (empty($issueType) || empty($title) || empty($message)) {
       http_response_code(400);
       return json_encode(array(
           'message' => 'Cannot proceed with empty fields.'
       ));
   }
   
   try {
       // Get the current datetime in PHP
       $createdAt = date('Y-m-d H:i:s');

       //AUTOMATED REPLY
       $fname = $utility->abbreviateName(mb_convert_case($_SESSION['user']['fname'], MB_CASE_TITLE));
$ai_message = "Hello ".$fname.",

Thank you for reaching out to our support team. We appreciate your message and would like to assure you that we will respond as soon as possible. Your satisfaction is our top priority.

Please note that our support team will never ask you for your password or any other sensitive information. We take the security and privacy of our users very seriously. If anyone claiming to be from our support team asks for such information, please do not provide it and report the incident to us immediately.

Once again, thank you for contacting us. We look forward to assisting you shortly.

Best regards,

Support System | Automated Reply";


   
       // Create a new support ticket
       $stmt = $this->conn->prepare('INSERT INTO SupportTicket (issue_type, title, sender_id, createdAt, updatedAt) VALUES (:issueType, :title, :sender_id, :createdAt, :createdAt)');
       $stmt->execute(array(':issueType' => $issueType, ':title' => $title, ':sender_id' => $user_id, ':createdAt' => $createdAt));
       $ticketId = $this->conn->lastInsertId();
   
       // Create the initial conversation/message for the ticket
       $stmt = $this->conn->prepare('INSERT INTO SupportConversation (ticket_id, sender_id, message, createdAt) VALUES (:ticket_id, :sender_id, :message, :createdAt)');
       $stmt->execute(array(':ticket_id' => $ticketId, ':sender_id' => $user_id, ':message' => $message, ':createdAt' => $createdAt));
        
       // Insert the automated reply.
       $stmt2 = $this->conn->prepare('INSERT INTO SupportConversation (ticket_id, sender_id, message, createdAt) VALUES (:ticket_id, :sender_id, :message, :createdAt)');
       $stmt2->execute(array(':ticket_id' => $ticketId, ':sender_id' => 1, ':message' => $ai_message, ':createdAt' => $createdAt));

       http_response_code(200);
       return json_encode(array(
           'message' => 'Ticket created successfully.',
           'ticket_id' => $ticketId
       ));
   } catch (PDOException $e) {
       http_response_code(500);
       return json_encode(array(
           'message' => 'Database error: ' . $e->getMessage()
       ));
   }
  }

  public function getConversation($ticket_id) {
      $allowedUserType = array('admin', 'moderator', 'officer', 'member');
      $allowedPermission = null;
      $section = 'bypass';
  
      $utility = new Utility();
      switch($utility->checkPermission($allowedUserType, $section, $allowedPermission)){
          case 10001:
              http_response_code(401);
              return json_encode(array(
                  'message' => 'Session already expired.',
                  'code' => 10001
              ));
              break;
  
          case 10002:
              http_response_code(401);
              return json_encode(array(
                  'message' => 'Unauthorized access',
                  'code' => 10002,
                  'debug' => 'Permission required: '.$allowedPermission
              ));
              break;
      }
  
      try {
          $stmt = $this->conn->prepare("
              SELECT SC.*, ST.title, ST.status, SC.createdAt AS messageCreatedAt
              FROM SupportConversation SC
              LEFT JOIN SupportTicket ST ON ST.id = SC.ticket_id
              WHERE SC.ticket_id = :ticket_id
              ORDER BY SC.createdAt ASC;
          ");
          $stmt->execute(array(':ticket_id' => $ticket_id));
          $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
          if (!$conversations) {
              http_response_code(401);
              return json_encode(array(
                  'message' => 'No conversations found for the ticket.',
                  'code' => 10003
              ));
          } else {
              // Fetch SupportTicket details
              $stmtTicket = $this->conn->prepare("SELECT * FROM SupportTicket WHERE id = :ticket_id");
              $stmtTicket->execute(array(':ticket_id' => $ticket_id));
              $ticket = $stmtTicket->fetch(PDO::FETCH_ASSOC);
      
              // Calculate time ago for each conversation
              $data = array();
              foreach ($conversations as $conversation) {
                  $createdAt = $conversation['createdAt'];
                  $timeAgo = $utility->getAgo($createdAt); // Call the function to calculate time ago
                  $conversation['time_ago'] = $timeAgo;
                  $data[] = $conversation;
              }
      
              $responseData = array(
                  'ticket' => $ticket,
                  'conversations' => $data
              );
      
              return json_encode(array(
                  'message' => 'Ticket and conversations fetched successfully.',
                  'code' => 10000,
                  'data' => $responseData
              ));
          }
      
          http_response_code(401);
          return json_encode(array(
              'message' => 'Failed to fetch ticket and conversations.',
              'code' => 10003
          ));
      
      } catch (PDOException $e) {
          echo 'Error: ' . $e->getMessage();
          exit();
      }

  }

  public function sendMessage(){
      $allowedUserType = array('admin', 'moderator', 'officer', 'member');
      $allowedPermission = null;
      $section = 'bypass';

      $utility = new Utility();
      switch($utility->checkPermission($allowedUserType, $section, $allowedPermission)){
            case 10001:
                http_response_code(401);
                return json_encode(array(
                    'message' => 'Session already expired.'
                ));
                break;

            case 10002:
                http_response_code(401);
                return json_encode(array(
                    'message' => 'Unauthorized access',
                    'debug' => 'Permission required: '.$allowedPermission
                ));
                break;
      }
    
      // Check if required parameters are provided
      if (!isset($_POST['message']) || !isset($_POST['ticket_id'])) {
          http_response_code(400);
          return json_encode(array(
              'message' => 'Missing required parameters'
          ));
      }

      // Fetch POST data
      $content = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8'); //Clean
      $ticketID = filter_input(INPUT_POST, 'ticket_id', FILTER_VALIDATE_INT);
      $user_id = $_SESSION['user']['id'];

      // Check if any of the variables are empty
      if (empty($content) || $ticketID === false) {
          http_response_code(400);
          return json_encode(array(
              'message' => 'Cannot proceed an empty message.'
          ));
      }
      
      try {
          // Inquire if the ticket_id exist or the ticket is already closed.
          $stmt1 = $this->conn->prepare('SELECT * FROM `SupportTicket` WHERE id = :std_id AND sender_id = :sender_id');
          $stmt1->execute(array(':std_id' => $ticketID, ':sender_id' => $user_id));
          $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

          if ($result1) {
              // Retrieve the last message createdAt
              $stmt2 = $this->conn->prepare('SELECT createdAt FROM `SupportConversation` WHERE ticket_id = :ticket_id ORDER BY createdAt DESC LIMIT 1');
              $stmt2->execute(array(':ticket_id' => $ticketID));
              $lastMessageCreatedAt = $stmt2->fetchColumn();
          
              // Calculate the time difference in minutes
              $currentTime = time();
              $lastMessageTime = strtotime($lastMessageCreatedAt);
              $timeDifference = $currentTime - $lastMessageTime;
              $timeDifferenceMinutes = round($timeDifference / 60);

              if ($timeDifferenceMinutes >= MESSAGE_COOLDOWN) {
                  // Get the current datetime in PHP
                  $createdAt = date('Y-m-d H:i:s');

                  if($result1['status'] === 'open' || $result1['status'] === 'pending'){
                    // Sufficient time has passed, user can send a new message
                    $stmt3 = $this->conn->prepare('INSERT INTO `SupportConversation`(`ticket_id`, `sender_id`, `message`, `createdAt`) VALUES (:ticket_id,:sender_id,:message,:createdAt)');
                    $stmt3->execute(array(':ticket_id' => $ticketID, ':sender_id' => $user_id, ':message' => $content, ':createdAt' => $createdAt));
            
                    // Set the status of the ticket to pending so he/she will be informed.
                    $stmt4 = $this->conn->prepare('UPDATE SupportTicket SET status = \'open\', updatedAt = :updatedAt  WHERE id = :ticketID');
                    $stmt4->bindParam(':ticketID', $ticketID);
                    $stmt4->bindParam(':updatedAt', $createdAt);
                    $stmt4->execute();

                    http_response_code(200);
                    return json_encode(array(
                        'message' => 'Message sent successfully.'
                    ));
                  }
                  
              } else {
                  // Not enough time has passed, user cannot send a new message yet
          
                  http_response_code(429); // HTTP 429 Too Many Requests
                  return json_encode(array(
                      'message' => 'Cannot send another message yet. Please wait before sending a new message.'
                  ));
              }
          }
          
          http_response_code(401);
          return json_encode(array(
              'message' => 'Unable to send message, please try again.'
          ));
      
      } catch (PDOException $e) {
          http_response_code(500);
          return json_encode(array(
              'message' => 'Database error: ' . $e->getMessage()
          ));
      }
  }

  public function getPendingCount(){
    $allowedUserType = array('admin', 'moderator', 'officer', 'member');
    $allowedPermission = null;
    $section = 'bypass';
    
    $utility = new Utility();
    switch($utility->checkPermission($allowedUserType, $section, $allowedPermission)){
        case 10001:
            http_response_code(401);
            return json_encode(array(
                'message' => 'Session already expired.',
                'code' => 10001
            ));
            break;
    
        case 10002:
            http_response_code(401);
            return json_encode(array(
                'message' => 'Unauthorized access',
                'code' => 10002,
                'debug' => 'Permission required: '.$allowedPermission
            ));
            break;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    try {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS PendingCount FROM `SupportTicket` WHERE `status` = 'pending' AND sender_id =:sender_id");
        $stmt->execute(array(':sender_id' => $user_id));
        $totalCount = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$totalCount) {
            http_response_code(401);
            return json_encode(array(
                'message' => 'Unable to count pending.',
                'code' => 10003
            ));
        } else {
            return json_encode(array(
                'message' => 'Pending count fetched successfully.',
                'code' => 10000,
                'data' => $totalCount
            ));
        }
    
        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch count of pending.',
            'code' => 10003
        ));
    
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        exit();
    }
  }



}

?>