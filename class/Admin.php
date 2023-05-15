<?php

require_once 'config/jwt.php';
require_once 'config/config.php';
require_once 'Database.php';
require_once 'Mailer.php';
require_once __DIR__.'./../assets/utility.php';


class Admin {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  /* USERS SECTION */
  
  public function getUsers() {
    $allowedUserType = array('admin', 'moderator', 'officer');
    $allowedPermission = 'user_view';
    $section = 'admin_panel';

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
      $stmt = $this->conn->prepare('SELECT us.id, us.std_id, sd.FullName, us.username, us.email, us.type FROM `users` us LEFT JOIN StudentData sd ON sd.StudentID = us.std_id WHERE 1');
      $stmt->execute();
      $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      if (!$students) {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch Users Data.',
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
            'message' => 'Failed to fetch Users Data.',
            'code' => 10003
        ));

    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }  
  }

  public function user_resend() {
    $allowedUserType = array('admin', 'moderator', 'officer');
    $allowedPermission = 'user_verify';
    $section = 'admin_panel';

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

    $target_id = $_POST['id'];

    try {
      $stmt = $this->conn->prepare('SELECT us.id, us.std_id, us.username, us.email, us.type, us.verification_code FROM `users` us WHERE id = :user_id');
      $stmt->execute(array(':user_id' => $target_id));
      $users = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$users) {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch Users Data.'
        ));

      } else {

        if($users['type'] != 'unverified'){
            return json_encode(array(
                'message' => 'Cannot resend an already verified account.'
            ));
        } else {
            // Generate another set of code
            $code = substr(uniqid(), 0, 15);

            // Update user
            $stmt3 = $this->conn->prepare('UPDATE users SET verification_code = :verification_code WHERE id = :id');
            $stmt3->execute(array(':verification_code' => $code , ':id' => $target_id));

            // Send verification email
            $to = $users['email'];
            $subject = 'Verify account - Cecilian Student Portal';
            $verification_link = SITE_URL . '/account/verify.php?code=' . $code;
            $message = sprintf(VERIFICATION_TEMPLATE, $users['username'], $verification_link, $verification_link);
            send_email($to, $subject, $message);

            return json_encode(array(
                'message' => 'Verification is sent successfully.'
            ));
        }
        
      }

        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to send Data.'
        ));

    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }  
  }

  public function user_delete() {
      $allowedUserType = array('admin');
      $allowedPermission = 'user_delete';
      $section = 'admin_panel';

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
  
      $target_id = $_POST['user_id'];
  
      try {
          $stmt = $this->conn->prepare('SELECT * FROM `users` WHERE id = :user_id');
          $stmt->execute(array(':user_id' => $target_id));
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
          if (!$user) {
              http_response_code(404);
              return json_encode(array(
                  'message' => 'User not found.'
              ));
          }
  
          // Perform the delete action using the ID
          $stmt = $this->conn->prepare('DELETE FROM `users` WHERE id = :user_id');
          $stmt->execute(array(':user_id' => $target_id));
          
          return json_encode(array(
              'message' => 'User deleted successfully.'
          ));
      } catch (PDOException $e) {
          echo 'Error: ' . $e->getMessage();
          exit();
      }
  }

  public function user_update() {
      $allowedUserType = array('admin', 'moderator');
      $allowedPermission = 'user_edit';
      $section = 'admin_panel';

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
       
    
      if ($_SESSION['user']['type'] === 'admin') {
          // Check if required parameters are provided
          if (!isset($_POST['id'], $_POST['username'], $_POST['email'], $_POST['type'])) {
            http_response_code(400);
            return json_encode(array(
              'message' => 'Missing required parameters'
            ));
          }
      } elseif ($_SESSION['user']['type'] === 'moderator') {
          // Check if required parameters are provided
          if (!isset($_POST['id'], $_POST['username'], $_POST['type'])) {
            http_response_code(400);
            return json_encode(array(
              'message' => 'Missing required parameters'
            ));
          }
      } 
    
      // Fetch POST data
      $id = $_POST['id'];
      $username = $_POST['username'];
      $email = $_POST['email'];
      $type = $_POST['type'];
    
      try {
        // Check if the user with the provided ID exists
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(array(':id' => $id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
          http_response_code(404);
          return json_encode(array(
            'message' => 'User not found'
          ));
        }

        // Restrict moderator of modifying some types.
        if($_SESSION['user']['type'] === 'moderator'){
            if($user['type'] === 'admin' || $user['type'] === 'moderator' || $user['type'] === 'unverified' || $type === 'admin' || $type === 'moderator' || $type === 'unverified'){
                http_response_code(401);
                return json_encode(array(
                  'message' => 'Unauthorized access'
                ));
            }
        }
        
    
        // Update the user information
        if ($_SESSION['user']['type'] === 'admin') {
          $update_stmt = $this->conn->prepare('UPDATE users SET username = :username, email = :email, type = :type WHERE id = :id');
          $update_stmt->execute(array(
            ':username' => $username,
            ':email' => $email,
            ':type' => $type,
            ':id' => $id
          ));
        } elseif ($_SESSION['user']['type'] === 'moderator') {
          $update_stmt = $this->conn->prepare('UPDATE users SET username = :username, type = :type WHERE id = :id');
          $update_stmt->execute(array(
            ':username' => $username,
            ':type' => $type,
            ':id' => $id
          ));
        }
    
        // Check if the update was successful
        if ($update_stmt->rowCount() > 0) {
          http_response_code(200);
          return json_encode(array(
            'message' => 'User updated successfully'
          ));
        } else {
          http_response_code(500);
          return json_encode(array(
            'message' => 'Failed to update user'
          ));
        }
    
      } catch (PDOException $e) {
        http_response_code(500);
        return json_encode(array(
          'message' => 'Database error: ' . $e->getMessage()
        ));
      }
  }

  public function user_query() {
      $allowedUserType = array('admin');
      $allowedPermission = 'user_query';
      $section = 'admin_panel';

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
      if (!isset($_POST['query'])) {
        http_response_code(400);
        return json_encode(array(
          'message' => 'Missing required parameters'
        ));
      }
    
      // Fetch POST data
      $query = $_POST['query'];

      try {
          $stmt = $this->conn->prepare('SELECT * FROM `StudentData` WHERE StudentID LIKE :query OR FullName LIKE :query');
          $stmt->execute(array(':query' => '%' . $query . '%'));
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
  
          if (!$result) {
            http_response_code(404);
            return json_encode(array(
              'message' => 'User not found',
            'student_id' => 'Cannot be found.',
            'student_fname' => 'Cannot be found.'
            ));
          } 

          http_response_code(200);
          return json_encode(array(
            'message' => 'Result found',
            'student_id' => $result['StudentID'],
            'student_fname' => $result['FullName']
          ));


      } catch (PDOException $e) {
          http_response_code(500);
          return json_encode(array(
            'message' => 'Database error: ' . $e->getMessage()
          ));
      }
  }

  public function user_create() {

      $allowedUserType = array('admin');
      $allowedPermission = 'user_add';
      $section = 'admin_panel';

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
      if (!isset($_POST['std_id']) && !isset($_POST['f_name']) && !isset($_POST['username']) && !isset($_POST['email']) && !isset($_POST['password']) && !isset($_POST['type'])) {
          http_response_code(400);
          return json_encode(array(
              'message' => 'Missing required parameters'
          ));
      }

      // Fetch POST data
      $student_id = $_POST['std_id'];
      $user_fname = $_POST['f_name'];
      $user_uname = $_POST['username'];
      $user_email = $_POST['email'];
      $user_pass = $_POST['password'];
      $user_type = $_POST['type'];

      // Check if any of the variables are empty
      if (empty($student_id) || empty($user_fname) || empty($user_uname) || empty($user_email) || empty($user_pass) || empty($user_type)) {
          http_response_code(400);
          return json_encode(array(
              'message' => 'Required fields should be filled in.'
          ));
      }
      
      try {
          $stmt = $this->conn->prepare('SELECT * FROM `StudentData` WHERE StudentID = :std_id AND FullName = :f_name');
          $stmt->execute(array(':std_id' => $student_id, ':f_name' => $user_fname));
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
          if (!$result) {
              http_response_code(404);
              return json_encode(array(
                  'message' => 'Cannot create an empty student.'
              ));
          }
      
          // Inquire if it has an account already.
          $stmt1 = $this->conn->prepare('SELECT * FROM `users` WHERE std_id = :std_id');
          $stmt1->execute(array(':std_id' => $student_id));
          $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
      
          if ($result1) {
              http_response_code(404);
              return json_encode(array(
                  'message' => 'Cannot duplicate student registration.'
              ));
          }

          // Inquire if it has an account already.
          $stmt2 = $this->conn->prepare('SELECT * FROM `users` WHERE email = :email OR username = :username');
          $stmt2->execute(array(':email' => $user_email, ':username' => $user_uname));
          $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
      
          if ($result2) {
              http_response_code(404);
              return json_encode(array(
                  'message' => 'Email and/or username is already taken.'
              ));
          }

          // Generate code for change pass as token
          $code = substr(uniqid(), 0, 15);

          // Hash the password
          $hashed_password = password_hash($user_pass, PASSWORD_ARGON2I);

          // All conditions are good, student can be registered
          $stmt3 = $this->conn->prepare('INSERT INTO users (std_id, email, username, password, type, verification_code) VALUES (:std_id, :email, :username, :password, :type, :verification_code)');
          $stmt3->execute(array(':std_id' => $student_id, ':email' => $user_email, ':username' => $user_uname, 'password' => $hashed_password, ':type' => $user_type , ':verification_code' => $code));

          // Send verification email
          $to = $user_email;
          $subject = 'Account Created - Cecilian Student Portal';
          $verification_link = SITE_URL . '/account/changepass.php?code=' . $code;
          $message = sprintf(VERIFICATION_TEMPLATE, $user_uname, $verification_link, $verification_link);
          send_email($to, $subject, $message);

          http_response_code(200);
          return json_encode(array(
              'message' => 'Account created successfully.'
          ));
      
      } catch (PDOException $e) {
          http_response_code(500);
          return json_encode(array(
              'message' => 'Database error: ' . $e->getMessage()
          ));
      }
  }

  /* STUDENTS SECTION */
  public function getStudents() {
    $allowedUserType = array('admin', 'moderator', 'officer');
    $allowedPermission = 'student_view';
    $section = 'admin_panel';

    $utility = new Utility();
    switch($utility->checkPermission($allowedUserType, $section, $allowedPermission)){
          case 10001:
              http_response_code(401);
              return json_encode(array(
                  'message' => 'Your session is already expired, kindly logout or refresh.',
                  'code' => 10001,
                  'debug' => 'Token already expired.'
              ));
              break;

          case 10002:
              http_response_code(401);
              return json_encode(array(
                  'message' => 'Not authorized to view this data.',
                  'code' => 10002,
                  'debug' => 'Permission required: '.$allowedPermission
              ));
              break;
    }

    try {
      $stmt = $this->conn->prepare('SELECT StudentData.*, users.type FROM StudentData LEFT JOIN users ON StudentData.StudentID = users.std_id;');
      $stmt->execute();
      $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      if (!$students) {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch Students Data.',
            'code' => 10003,
            'debug' => "test"
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
      http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch Students Data.',
            'code' => 10003,
            'debug' => $e->getMessage()
        ));
      exit();
    }  
  }

  public function student_delete() {
      $allowedUserType = array('admin');
      $allowedPermission = 'student_delete';
      $section = 'admin_panel';

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
  
      $target_id = $_POST['student_id'];
  
      try {
          $stmt = $this->conn->prepare('SELECT * FROM `users` WHERE std_id = :student_id');
          $stmt->execute(array(':student_id' => $target_id));
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
          if ($user) {
              http_response_code(404);
              return json_encode(array(
                  'message' => 'Unregistering account is required before deleting.'
              ));
          }
  
          // Perform the delete action using the ID
          $stmt = $this->conn->prepare('DELETE FROM `StudentData` WHERE StudentID = :student_id');
          $stmt->execute(array(':student_id' => $target_id));
          
          return json_encode(array(
              'message' => 'Student deleted successfully.'
          ));
      } catch (PDOException $e) {
          echo 'Error: ' . $e->getMessage();
          exit();
      }
  }

  /* EVENT/CALENDAR/ACTIVITY SECTION */
  public function event_create() {

      $allowedUserType = array('admin', 'moderator','officer');
      $allowedPermission = 'event_add';
      $section = 'admin_panel';

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
      if (!isset($_POST['name']) || !isset($_POST['start']) || !isset($_POST['end']) || !isset($_POST['allDay']) || !isset($_POST['noClass']) || !isset($_POST['permissions']) || !isset($_POST['access'])) {
          http_response_code(400);
          echo json_encode(array(
              'message' => 'Missing required parameters'
          ));
          exit;
      }

      // Fetch POST data
      $name = $_POST['name'];
      $description = "test";
      $start_date = $_POST['start']['date'];
      $start_time = !empty($_POST['start']['time']) ? $_POST['start']['time'] : '00:00';
      $end_date = $_POST['end']['date'];
      $end_time = !empty($_POST['end']['time']) ? $_POST['end']['time'] : '00:00';
      $location = $_POST['location'];
      $all_day = $_POST['allDay'];
      $no_class = $_POST['noClass'];
      $edit_permissions = $_POST['permissions']['edit'];
      $delete_permissions = $_POST['permissions']['delete'];
      $access = $_POST['access']; //departments

      $start_datetime = $start_date . ' ' . $start_time . ':00';
      $end_datetime = $end_date . ' ' . $end_time . ':00';
      
      // Check if any of the required variables are empty
      if (empty($name) || empty($start_date) || empty($start_time) || empty($all_day) || empty($no_class) || empty($access)) {
          http_response_code(400);
          return json_encode(array(
              'message' => 'Required fields should be filled in.'
          ));
      }

      // Check if end_date and end_time are required if the allDay is set to true
      if (!$all_day && (empty($start_date) || empty($end_date))) {
          http_response_code(400);
          return json_encode(array(
              'message' => 'Start date and end date are required.'
          ));
      }
      
      // Check if permission is required
      if ($_SESSION['user']['type'] === 'admin' && !empty($permission)) {
          http_response_code(400);
          return json_encode(array(
              'message' => 'Permission settings is required.'
          ));
      } else {
        // Assign permission values to variables
        $edit_moderator = $_POST['permissions']['edit']['moderator'];
        $edit_teacher = $_POST['permissions']['edit']['teacher'];
        $edit_officer = $_POST['permissions']['edit']['officer'];
        $delete_moderator = $_POST['permissions']['delete']['moderator'];
        $delete_teacher = $_POST['permissions']['delete']['teacher'];
        $delete_officer = $_POST['permissions']['delete']['officer'];

        $permissions = array(
            'edit' => array(
                'moderator' => $edit_moderator,
                'teacher' => $edit_teacher,
                'officer' => $edit_officer
            ),
            'delete' => array(
                'moderator' => $delete_moderator,
                'teacher' => $delete_teacher,
                'officer' => $delete_officer
            )
        );
        
        $permissions_json = json_encode($permissions);
      }

      // Prepare the departments
      $access_arr = array(
          'bsit' => isset($access['bsit']) ? true : false,
          'bsba' => isset($access['bsba']) ? true : false,
          'bsed' => isset($access['bsed']) ? true : false,
          'beed' => isset($access['beed']) ? true : false,
          'bshm' => isset($access['bshm']) ? true : false,
          'bstm' => isset($access['bstm']) ? true : false,
          'bscrim' => isset($access['bscrim']) ? true : false
      );
      
      // Convert to JSON
      $access_json = json_encode($access_arr);
      
      try {
          $stmt = $this->conn->prepare('INSERT INTO `eventsCalendar`(`addedBy`, `title`, `location`, `start_date`, `end_date`, `allDay`, `noClass`, `access`, `permission`, `description`) VALUES (:my_id, :title, :location, :start_date, :end_date, :all_day, :no_class, :access, :permission, :description)');
          $stmt->execute(array(':my_id' => $_SESSION['user']['id'], ':title' => $name, ':location' => $location, ':start_date' => $start_datetime, ':end_date' => $end_datetime, ':all_day' => $all_day, ':no_class' => $no_class, ':access' => $access_json, ':permission' => $permissions_json, ':description' => $description));
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
          http_response_code(200);
              return json_encode(array(
                  'message' => 'Event created successfully.',
                  'debug' => $result
              ));
      
      } catch (PDOException $e) {
          http_response_code(500);
          return json_encode(array(
              'message' => 'Database error: ' . $e->getMessage()
          ));
      }
  }
  
}

?>