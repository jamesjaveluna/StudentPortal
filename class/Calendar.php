<?php

require_once 'config/jwt.php';
require_once 'config/config.php';
require_once 'Database.php';
require_once 'Mailer.php';
require_once __DIR__.'./../assets/utility.php';


class Calendar {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
  }
  
  public function getEvents() {
    $allowedUserType = array('admin', 'moderator', 'officer');
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
                  'code' => 10002
              ));
              break;
    }

    try {
       $stmt = $this->conn->prepare('SELECT id, title, start_date as start, end_date as end, location, allDay, noClass, access, permission, addedBy FROM eventsCalendar');
       $stmt->execute();
       $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

       if (!$events) {
           http_response_code(401);
           return json_encode(array(
               'message' => 'Failed to fetch events.',
               'code' => 10003
           ));
       } else {
           return json_encode(array(
               'message' => 'Events fetched successfully.', 
               'code' => 10000, 
               'data' => $events
           ));
       }

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        exit();
    }
  }

  public function addEvent(){
    return json_encode(array(
            'message' => 'Connected',
            'code' => 10002
    ));
  }

}

?>