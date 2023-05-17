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
                  'code' => 10002
              ));
              break;
    }

    try {
       $stmt = $this->conn->prepare('SELECT id, title, start_date as start, end_date as end, location, allDay, noClass, isExam, isHoliday, access, permission, addedBy FROM eventsCalendar');
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

  /*public function getEventToday() {
      $events_raw = json_decode($this->getEvents(), true);
  
      if ($events_raw['code'] !== 10000) {
          return json_encode(array(
              'message' => 'Unavailable',
              'code' => 10004
          ));
      }
  
      $events_data = $events_raw['data'];
      $current_date = date("Y-m-d");
      $current_datetime = date("Y-m-d H:i:s");
  
      $events_today = array();
  
      //0 = Ended
      //1 = Ongoing
      foreach ($events_data as $event) {
          if ($event['allDay'] === 'true') {
              $start_date = date("Y-m-d", strtotime($event['start']));
              $end_date = date("Y-m-d", strtotime($event['end']));
  
              if ($start_date <= $current_date && $current_date <= $end_date) {
                  if (isset($event['noClass']) && $event['noClass'] === true) {
                      $events_today[] = $event;
                  } else {
                      $event['status'] = ($current_date === $start_date) ? 1 : 0;
                      $events_today[] = $event;
                  }
              }
          } else {
              $event_datetime = date("Y-m-d H:i:s", strtotime($event['start']));
  
              if ($event_datetime === $current_datetime) {
                  if (isset($event['noClass']) && $event['noClass'] === true) {
                      $events_today[] = $event;
                  } else {
                      $event_end_datetime = date("Y-m-d H:i:s", strtotime($event['end']));
                      $event['status'] = ($current_datetime <= $event_end_datetime) ? 1 : 0;
                      $events_today[] = $event;
                  }
              }
          }
      }
  
      if (count($events_today) === 0) {
          return json_encode(array(
              'message' => 'No Event',
              'code' => 10001
          ));
      }
  
      // Check access array for each event
      $filtered_events = array();
      $user_course = strtolower($_SESSION['user']['Course']);
  
      foreach ($events_today as $event) {
              $access = json_decode($event['access'], true);
  
              if (isset($access[$user_course]) && $access[$user_course] === true) {
                  $filtered_events[] = $event;
              }
      }
  
      if (count($filtered_events) === 0) {
          return json_encode(array(
              'message' => 'No Event accessible for your course',
              'code' => 10003
          ));
      }
  
      $message = '';
      if (count($filtered_events) === 1) {
          $message = 'Event fetched successfully.';
          $code = 10000;
      } else {
          $message = count($filtered_events) . ' events scheduled.';
          $code = 10002;
      }
  
      return json_encode(array(
          'message' => $message,
          'data' => $filtered_events,
          'code' => $code
      ));
  } */

  public function getEventToday() {
      $events_raw = json_decode($this->getEvents(), true);
  
      if ($events_raw['code'] !== 10000) {
          return json_encode(array(
              'message' => 'Failed to connect Calendar System.',
              'code' => 10004
          ));
      }
  
      $events_data = $events_raw['data'];
      $current_date = date("Y-m-d");
      $current_datetime = date("Y-m-d H:i:s");

      $events_today = array();
      
      foreach ($events_data as $event) {
          if($event['allDay'] === 'true' && $event['isExam'] === 'false'){
                $start_date = date("Y-m-d", strtotime($event['start']));
                $end_date = date("Y-m-d", strtotime($event['end']));
  
                if ($start_date <= $current_date && $current_date <= $end_date) {
                    $event['status'] = 1;
                    $events_today[] = $event;
                }
          } else {
                $event_start_datetime = date("Y-m-d H:i:s", strtotime($event['start']));
                $event_end_datetime = date("Y-m-d H:i:s", strtotime($event['end']));

                if ($event_end_datetime < $current_datetime) {
                    $event['status'] = 0;
                    $events_today[] = $event;
                } elseif ($event_start_datetime <= $current_datetime && $current_datetime <= $event_end_datetime) {
                    $event['status'] = 1;
                    $events_today[] = $event;
                }
          }
          
      }

      // Check if events is empty
      if (count($events_today) === 0) {
          return json_encode(array(
              'message' => 'No Event',
              'code' => 10001
          ));
      }

      // Check access array for each event
      $filtered_events = array();
      $user_course = strtolower($_SESSION['user']['Course']);

      foreach ($events_today as $event) {
          $access = json_decode($event['access'], true);

          if (isset($access[$user_course]) && $access[$user_course] === true) {
              $filtered_events[] = $event;
          }
      }

      if (count($filtered_events) === 0 && count($events_today) > 0) {
          return json_encode(array(
              'message' => 'No Event accessible for your course',
              'data' => $events_today,
              'code' => 10003
          ));
      }

  
      $message = '';
      if (count($filtered_events) === 1) {
          $message = 'Event fetched successfully.';
          $code = 10000;
      } else {
          $message = count($filtered_events) . ' events scheduled.';
          $code = 10002;
      }

      return json_encode(array(
          'message' => $message,
          'data' => $filtered_events,
          'code' => $code
      ));
  }






}

?>