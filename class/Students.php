<?php

require_once 'config/config.php';
require_once 'config/jwt.php';
require_once 'Database.php';


class Students {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function getStudents() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $user_id = $_SESSION['user']['id'];
    $user_type = $_SESSION['user']['type'];
    //$user_type = isset($_SESSSION['user']['type']) ? $_SESSSION['user']['type'] : 0;
    $user_token = $_SESSION['user']['token'];

    // Check if user_type is eligible with this request.
    if ($user_type !== 'admin' && $user_type !== 'moderator' && $user_type !== 'officer') {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Unauthorized Request',
            'code' => 10001
        ));
    }

    // Check if token is still valid/expired.
    $decoded_token = verifyToken($user_token);
    if($decoded_token['user_id'] != $user_id){
        http_response_code(401);
        return json_encode(array(
            'message' => 'Token already expired.',
            'code' => 10002
        ));
    }

    try {
      $stmt = $this->conn->prepare('SELECT * FROM `StudentData` WHERE 1');
      $stmt->execute();
      $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      if (!$students) {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch Students Data.',
            'code' => 10003
        ));
      } else {
        return json_encode(array(
            'message' => 'data fetched successfully.', 
            'code' => 10000, 
            'data' => $students
        ));
      }

        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch Students Data.',
            'code' => 10003
        ));

    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }  


    
  }
  
}

?>