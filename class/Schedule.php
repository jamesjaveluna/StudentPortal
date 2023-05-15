<?php

require_once 'config/config.php';
require_once 'config/jwt.php';
require_once 'Database.php';
require_once __DIR__.'./../assets/utility.php';


class Schedule {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function getSchedule() {
    $allowedUserType = array('admin', 'moderator', 'officer', 'member');
    $allowedPermission = 'schedule_view';
    $section = 'user_panel';

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

    $target_id = $_SESSION['user']['StudentID'];

    try {
      $stmt = $this->conn->prepare('SELECT * FROM `SubjectData` WHERE student_id = :std_id');
      $stmt->execute(array('std_id' => $target_id));
      $schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $updatedDate = date('Y-m-d');
      
      if (!$schedule) {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch schedule.',
            'code' => 10003
        ));
      } else {

         // Add day field to each schedule item
        foreach ($schedule as &$item) {
          
          // Fix Day
          $item['day'] = $utility->fixDay($item['day']);

          //Fix time
          if ($item['time'] !== 'TBA') {
            $fixTime = $utility->fix_time_format($item['time']);
            $item['civilian_time']['start_time'] = $fixTime['start_time'];
            $item['civilian_time']['end_time'] = $fixTime['end_time'];

            $item['military_time']['start_time'] = $utility->convertToMilitaryTime($fixTime['start_time']);
            $item['military_time']['end_time'] = $utility->convertToMilitaryTime($fixTime['end_time']);
          }

          //$item['COURSES'] = $item;

        }

        // Sort the schedule based on start_time
        usort($schedule, function($a, $b) {
          if (isset($a['military_time']['start_time']) && isset($b['military_time']['start_time'])) {
            return strtotime($a['military_time']['start_time']) - strtotime($b['military_time']['start_time']);
          }
          return 0;
        });

        // Assign event to course by code
        $event_counter = 1;
        $event_map = array();
        foreach ($schedule as &$item) {
          $code = $item["code"];
          if (!array_key_exists($code, $event_map)) {
            $event_map[$code] = "event-" . $event_counter;
            $event_counter++;
          }
          $item["data-event"] = $event_map[$code];
        }

        // Group courses by day
        $grouped_courses = [];
        foreach ($schedule as $course) {
            $days = explode(",", $course["day"]);
            foreach ($days as $day) {
                $day = trim($day); // remove leading/trailing whitespace
                if (!array_key_exists($day, $grouped_courses)) {
                    $grouped_courses[$day] = []; // create an empty array for the day
                }
                $grouped_courses[$day][] = $course; // add the course to the day's array
            }
        }

        return json_encode(array(
            'message' => 'schedule fetched successfully.', 
            'code' => 10000, 
            'data' => $grouped_courses,
            'last_update_display' => date('F d, Y', strtotime($schedule[0]['updatedDate']))
        ));
      }

        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch schedule.',
            'code' => 10003
        ));

    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }  
  }

  public function getScheduleToday(){
        $schedule_raw =  json_decode($this->getSchedule(), true);

        $current_day = strtoupper(date('l'));
        $current_time = date("H:i");

        if($schedule_raw['code'] === 10000){
            $schedule_data = $schedule_raw['data'];
        } else {
            return json_encode(array(
                'message' => 'Unavailable',
                'code' => 10004
            ));
        }

        // 1. Count the number of subjects of the day
        $num_subjects = isset($schedule_data[$current_day]) ? count($schedule_data[$current_day]) : 0;
        //$num_subjects = count($schedule_data[$current_day]);
        if($num_subjects === 0 || $num_subjects === NULL){
            return json_encode(array(
                'message' => 'No Class',
                'code' => 10001
            ));
        }

        // 2. Check if there is a subject with a similar start_time and end_time as the current time
        $found_subject = false;
        $last_subject = end($schedule_data[$current_day]); // Get the last subject of the day
        $next_subject = null;
        foreach ($schedule_data[$current_day] as $subject) {
            if($current_time >= $subject['military_time']['start_time'] && $current_time <= $subject['military_time']['end_time']){
                $found_subject = true;
                $time_display_format = $subject['civilian_time']['start_time'].' - '.$subject['civilian_time']['end_time'];
                // Return the subject if it matches the current time
                return json_encode(array(
                    'message' => 'Subject found',
                    'code' => 10000,
                    'data' => array(
                        'code' => $subject['code'],
                        'description' => $subject['description'],
                        'time' => $time_display_format,
                        'room_name' => $subject['room_name']
                    ),
                    'next_subject' => $this->getNextSubject($schedule_data[$current_day], $subject['military_time']['end_time'])
                ));
            } else if($next_subject === null && $current_time < $subject['military_time']['start_time']) {
                $next_subject = array(
                    'code' => $subject['code'],
                    'description' => $subject['description'],
                    'time' => $subject['civilian_time']['start_time'],
                    'room_name' => $subject['room_name']
                );
            }
        }

        // 3. Check if last subject of the day is already done
        if($current_time >= $last_subject['military_time']['end_time']){
            return json_encode(array(
                'message' => 'Completed',
                'code' => 10003
            ));
        }

        // If no subject matches the current time and the last subject is not yet done, return 'Next subject' and the details of the next subject
        return json_encode(array(
            'message' => 'Vacant',
            'code' => 10002,
            'data' => $next_subject
        ));
  }

  private function getNextSubject($subjects, $current_time){
    $next_subject = null;
    foreach($subjects as $subject){
        if($current_time < $subject['military_time']['start_time']){
            $next_subject = $subject;
            break;
        }
    }

    if($next_subject != null){
        $time_display_format = $next_subject['civilian_time']['start_time'].' - '.$next_subject['civilian_time']['end_time'];
        return array(
            'code' => $next_subject['code'],
            'description' => $next_subject['description'],
            'time' => $time_display_format,
            'room_name' => $next_subject['room_name']
        );
    } else {
        return null;
    }
  }

}

?>