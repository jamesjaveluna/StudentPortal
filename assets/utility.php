<?php

require_once __DIR__.'./../class/config/jwt.php';
require_once __DIR__.'./../class/config/config.php';
require_once __DIR__.'./../class/Database.php';


class Utility {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  public function abbreviateName($name) {
      $words = explode(',', $name);
      $lastName = trim($words[0]);
      $firstName = trim($words[1]);
      $initial = substr($firstName, 0, 1);
      return $lastName . ', ' . $initial . '.';
  }

  public function convertToMilitaryTime($time) {
    $time_parts = explode(' ', $time); // Splitting time and period
    $time = $time_parts[0]; // Extracting the time part
    $period = strtoupper(trim($time_parts[1])); // Extracting the period (AM/PM)

    $time_parts = explode(':', $time);
    $hour = (int)$time_parts[0];
    $minute = (int)$time_parts[1];

    if (($period === 'PM' || $period === 'NN') && $hour !== 12) {
        $hour += 12; // Add 12 to convert to PM hours (except for 12 PM)
    } elseif ($period === 'AM' && $hour === 12) {
        $hour = 0; // Special case: 12 AM should be 0 hours
    }

    return sprintf('%02d:%02d', $hour, $minute);
  }

  function add_leading_zero($number) {
    if (($number >= 1 && $number <= 9) || ($number >= 6 && $number <= 9)) {
        return "0" . $number;
    } else {
        return $number;
    }
}

  function fix_time_format($time_range) {
    $time_range_parts = explode('-', $time_range);
    $start_time = trim($time_range_parts[0]);
    $end_time = trim($time_range_parts[1]);

    //echo 'START_TIME: '.$start_time.'<br>';
    //echo 'END_TIME: '.$end_time.'<br>';
    //10:30-12:00 NN

    // handle case where meridian is not separated by space
    if (strpos($end_time, ':') !== false) {
        $colon_pos = strpos($end_time, ':');
        $first_two_digits = substr($end_time, $colon_pos-2, 2);
        $meridian_pos = $colon_pos+2;
        $end_time_length = strlen($end_time);
        if (($meridian_pos+2) <= $end_time_length && $end_time[$meridian_pos+1] != ' ') {
            $end_time = substr_replace($end_time, ' ', $meridian_pos+1, 0);
        }
    }

    //echo 'END_TIME(modified): '.$end_time.'<br><br>';

    $start_time_parts = explode(' ', $start_time); //start_time: 10:30
    $start_time_hours_minutes = $start_time_parts[0]; //10:30
    //$start_time_period = $start_time_parts[1]; (no meridian)

    $end_time_parts = explode(' ', $end_time); //12:00 NN
    $end_time_hours_minutes = $end_time_parts[0]; //12:00
    $end_time_period = $end_time_parts[1]; //NN

    $start_time_hours_minutes_parts = explode(':', $start_time_hours_minutes); //10:30
    $start_time_hours = $start_time_hours_minutes_parts[0]; //10 (hour)
    $start_time_minutes = $start_time_hours_minutes_parts[1]; //30 (minute)

    $end_time_hours_minutes_parts = explode(':', $end_time_hours_minutes); //12:00
    $end_time_hours = $end_time_hours_minutes_parts[0]; //12 (hour)
    $end_time_minutes = $end_time_hours_minutes_parts[1]; //00 (minute)

    $start_time_hours = $this->add_leading_zero($start_time_hours);
    //$end_time_hours  = $this->add_leading_zero($end_time_hours);

    switch(strtoupper($end_time_period)){
        case 'AM':
            $start_time_period = 'AM';
        break;

        case 'NN':
            $start_time_period = 'AM';
        break;

        case 'PM':
            $start_time_period = 'PM';
        break;
    }

    if ($end_time_hours > 12) {
        $end_time_hours = $end_time_hours - 12;
        $end_time_period = 'PM';
    }

    $start_time_fixed = $start_time_hours . ':' . $start_time_minutes . ' ' . $start_time_period;
    $end_time_fixed = $end_time_hours . ':' . $end_time_minutes . ' ' . $end_time_period;


    return array(
        'start_time' => $start_time_fixed,
        'end_time' => $end_time_fixed
    );
  }

  public function fixDay($day)
  {
      $day = strtoupper(str_replace(' ', '', trim($day))); // Convert to uppercase and remove leading/trailing spaces
      
      $dayMappings = [
          'MON' => 'MONDAY',
          'M' => 'MONDAY',
          'MONDAY' => 'MONDAY',
          'TUE' => 'TUESDAY',
          'T' => 'TUESDAY',
          'TUESDAY' => 'TUESDAY',
          'WED' => 'WEDNESDAY',
          'W' => 'WEDNESDAY',
          'WEDNESDAY' => 'WEDNESDAY',
          'THU' => 'THURSDAY',
          'THUR' => 'THURSDAY',
          'THURS' => 'THURSDAY',
          'TH' => 'THURSDAY',
          'THURSDAY' => 'THURSDAY',
          'FRI' => 'FRIDAY',
          'F' => 'FRIDAY',
          'FRIDAY' => 'FRIDAY',
          'SAT' => 'SATURDAY',
          'S' => 'SATURDAY',
          'SATURDAY' => 'SATURDAY',
          'SUN' => 'SUNDAY',
          'SU' => 'SATURDAY',
          'SUNDAY' => 'SUNDAY',
          'MW' => 'MONDAY,WEDNESDAY',
          'MWF' => 'MONDAY,WEDNESDAY,FRIDAY',
          'TTH' => 'TUESDAY,THURSDAY',
          'TTH,F' => 'TUESDAY, THURSDAY, FRIDAY',
          'TTH, FRI' => 'TUESDAY, THURSDAY, FRIDAY',
          'TTHF' => 'TUESDAY, THURSDAY, FRIDAY',
          'MON,WED' => 'MONDAY,WEDNESDAY',
          'WED,THURS' => 'WEDNESDAY,THURSDAY',
          'WED,THUR' => 'WEDNESDAY,THURSDAY',
          'WED,FRI' => 'WEDNESDAY,FRIDAY',
          'FRI,SAT' => 'FRIDAY,SATURDAY',
          'MON & WED' => 'MONDAY,WEDNESDAY',
          // Add more mappings as needed
      ];
      
      if (isset($dayMappings[$day])) {
          return $dayMappings[$day];
      }
      
      return $day; // Return the original value if no mapping is found
  }
  
  public function checkPermission($requiredTypes, $section, $permission){
      /*    RESPONSES
            10001 = Session already expired.
            10002 = Unauthorized access.
      */

      // Check if session already started
      if (session_status() !== PHP_SESSION_ACTIVE) {
          session_start();
      }

      $user_token = isset($_SESSION['user']['token']) ? $_SESSION['user']['token'] : 'guest';
      $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'guest';

      if($user_token === 'guest'){
            return 10002; 
      } else {
            $decoded_token = verifyToken($user_token);
            if ($decoded_token['user_id'] !== $user_id) {
              return 10001;
            }
      }

      // Fetch live data
      $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
      $stmt->execute(array(':id' => $user_id));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Check if user is logged in as one of the required types
      if (!in_array($user['type'], $requiredTypes)) {
          return 10002;
      }

      if($section !== 'bypass'){
        $uperm = json_decode($user['permission'], true); //user_permissions

        switch($section){
              case 'user_panel':
                  $permissions = $uperm['user_permissions']['user_panel'];
                  break;

              case 'admin_panel':
                  $permissions = $uperm['user_permissions']['admin_panel'];
                  break;

              case 'forum':
                  $permissions = $uperm['user_permissions']['forum'];
                  break;

              default:
                  return 10002;
                  break;
        }

        // Check if user is logged in the required permission
        if (!in_array($permission, $permissions)) {
            return 10002;
        }
      }

      //Update user_session
      foreach ($user as $variable_name => $variable_value) {
          if ($variable_name !== 'password') {
            $_SESSION['user'][$variable_name] = $variable_value;
          }
      }

  }

 public function addMeridiem($time) {
        // Separate the start time and end time
        $times = explode('-', $time);
        $start_time = trim($times[0]);
        $end_time = trim($times[1]);

        // Extract meridian from the end time
        $end_meridiem = strtoupper(substr($end_time, -2));

        // Determine meridian for the start time
        $start_hour = intval(substr($start_time, 0, 2));
        $start_minute = intval(substr($start_time, 3, 2));

        echo 'Start Hour: '.$start_hour.'<br>';
        echo 'Start Hour: '.$start_minute.'<br>';

        // If end meridian is AM or PM, use the same meridian for the start time
        if ($end_meridiem === 'AM' || $end_meridiem === 'PM') {
            $start_meridiem = $end_meridiem;
        } elseif ($end_meridiem === 'NN') { // If end meridian is NN, set start meridian as AM
            $start_meridiem = 'AM';
        } else { // Handle cases when meridian is not provided
            if ($start_hour >= 1 && $start_hour <= 6) {
                $start_meridiem = 'PM';
            } elseif (($start_hour >= 7 && $start_hour <= 11) || ($start_hour === 12 && $start_minute === 0)) {
                $start_meridiem = 'AM';
            } else {
                $start_meridiem = 'PM';
            }
        }

        // Format start time with meridian
        $start_hour %= 12;
        if ($start_hour === 0) {
            $start_hour = 12;
        }
        $start_time_with_meridiem = sprintf('%d:%02d %s', $start_hour, $start_minute, $start_meridiem);

        // Format end time with meridian
        $end_hour = intval(substr($end_time, 0, 2));
        $end_minute = intval(substr($end_time, 3, 2));
        $end_hour %= 12;
        if ($end_hour === 0) {
            $end_hour = 12;
        }
        $end_time_with_meridiem = sprintf('%d:%02d %s', $end_hour, $end_minute, $end_meridiem);

        // Return the updated time range
        return $start_time_with_meridiem . ' - ' . $end_time_with_meridiem;
 }

 public function addMeridiem2($times){
       echo 'Given: '.$times.'<br><br>';
       $time = explode('-', $times);
       $start_time = trim($time[0]);
       $end_time = trim($time[1]);
 
       echo 'Start_time: '.$start_time.'<br>';
       $start_time_parts = explode(' ', $start_time);
       $start_hour = str_pad((int)explode(':', $start_time_parts[0])[0], 2, '0', STR_PAD_LEFT);
       $start_minute = (int)explode(':', $start_time_parts[0])[1];
       $start_meridiem = $start_time_parts[1];
       echo 'Start_hour: '.$start_hour.'<br>';
       echo 'Start_minute: '.$start_minute.'<br>';
       echo 'Start_meridiem: '.$start_meridiem.'<br><br>';
 
       echo 'End_time: '.$end_time.'<br>';
       $end_time_parts = explode(' ', $end_time);
       $end_hour = str_pad((int)explode(':', $end_time_parts[0])[0], 2, '0', STR_PAD_LEFT);
       $end_minute = (int)explode(':', $end_time_parts[0])[1];
       $end_meridiem = $end_time_parts[1];
       echo 'End_hour: '.$end_hour.'<br>';
       echo 'End_minute: '.$end_minute.'<br>';
       echo 'End_meridiem: '.$end_meridiem.'<br><br>';
 
       // Extract meridian from the end time
       $end_meridiem = strtoupper(substr($end_time, -2));
 
       // If end meridian is AM or PM, use the same meridian for the start time
       if ($end_meridiem === 'AM' || $end_meridiem === 'PM') {
           $start_meridiem = $end_meridiem;
       } elseif ($end_meridiem === 'NN') { // If end meridian is NN, set start meridian as AM
           $start_meridiem = 'AM';
       } else { // Handle cases when meridian is not provided
           if ($start_hour >= 1 && $start_hour <= 6) {
               $start_meridiem = 'PM';
           } elseif (($start_hour >= 7 && $start_hour <= 11) || ($start_hour === 12 && $start_minute === 0)) {
               $start_meridiem = 'AM';
           } else {
               $start_meridiem = 'PM';
           }
       }
 
       // Format start time with meridian
       $start_hour %= 12;
       if ($start_hour === 0) {
           $start_hour = 12;
       }
       $start_time_with_meridiem = sprintf('%d:%02d %s', $start_hour, $start_minute, $start_meridiem);
 
       // Format end time with meridian
       $end_hour = intval(substr($end_time, 0, 2));
       $end_minute = intval(substr($end_time, 3, 2));
       $end_hour %= 12;
       if ($end_hour === 0) {
           $end_hour = 12;
       }
       $end_time_with_meridiem = sprintf('%d:%02d %s', $end_hour, $end_minute, $end_meridiem);
 
       // Return the updated time range
       echo $start_time_with_meridiem . ' - ' . $end_time_with_meridiem;
 }

 public function getAgo($createdAt){
    $currentTime = time();
    $messageTime = strtotime($createdAt);
    $timeDifference = $currentTime - $messageTime;

    if ($timeDifference < 60) {
        return $timeDifference . ' seconds ago';
    } elseif ($timeDifference < 3600) {
        return round($timeDifference / 60) . ' minutes ago';
    } elseif ($timeDifference < 86400) {
        return round($timeDifference / 3600) . ' hours ago';
    } elseif ($timeDifference < 604800) {
        return round($timeDifference / 86400) . ' days ago';
    } elseif ($timeDifference < 2419200) {
        return round($timeDifference / 604800) . ' weeks ago';
    } else {
        return round($timeDifference / 2419200) . ' months ago';
    }
 }



}

?>