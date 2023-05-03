<?php

require_once 'config/jwt.php';
require_once 'config/config.php';
require_once 'Database.php';
require_once 'Mailer.php';


class User {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
  }
  
  // verify account
  public function verify() {
    session_start();

    if (!isset($_POST['code'], $_POST['password'], $_POST['repassword'])) {
        http_response_code(400);
        return json_encode(array(
            'message' => 'Request is missing.'
        ));
    }

    // Fetch POST data
    $code = $_POST['code'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    // Recaptcha System
    if(RECAPTCHA_ENABLED === true){
        $recaptcha_code = $_POST['recaptcha_response'];
        $remote_ip = $_SERVER['REMOTE_ADDR'];

        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = array('secret' => RECAPTCHA_SECRET_KEY, 'response' => $recaptcha_code, 'remoteip' => $remote_ip);
        
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        $json = json_decode($result);
        
        if (!$json->success) {
            http_response_code(401);
            return json_encode(array(
                'message' => 'RECAPTCHA error.'
            ));
        }
    }

    try {
      $stmt = $this->conn->prepare('SELECT StudentData.StudentID, StudentData.FullName as fname, StudentData.Birthday, StudentData.Gender, StudentData.Address, StudentData.Status, StudentData.Semester, users.id, users.username, users.password, users.type, users.verification_code FROM StudentData LEFT JOIN users ON StudentData.StudentID = users.std_id WHERE users.verification_code = :code AND users.type = "unverified"');
      $stmt->execute(array(':code' => $code));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$user) {
        http_response_code(401);
        return json_encode(array('message' => 'Invalid/expired verification code.'));
      }
      
      //if (password_verify($password, $user['password'])) {
      if ($password === $repassword) {

        // TODO: Update user with the password WHERE code = $code
        $hashed_password = password_hash($password, PASSWORD_ARGON2I);
        
        // Update user with the new hashed password
        $update_stmt = $this->conn->prepare('UPDATE users SET password = :password, type = "member" WHERE verification_code = :code');
        $update_stmt->execute(array(':password' => $hashed_password, ':code' => $code));


        // Store user data in session
        foreach ($user as $variable_name => $variable_value) {
          if ($variable_name !== 'password' || $variable_name !== 'verification_code') {
            $_SESSION['user'][$variable_name] = $variable_value;
          }
        }

        $token = generateToken($user['id']);
         $_SESSION['user']['token'] = $token;
        $response = array(
            'token' => $token,
            'message' => 'Account verified successfully.'
        );

        $session_id = session_id();
        setcookie('PHPSESSID', $session_id, time() + (7 * 24 * 60 * 60), '/');
        
        return json_encode($response);
      }

        http_response_code(401);
        return json_encode(array('message' => 'Password do not match.'));

    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }  
  }

  // login user and store user data in session
  public function login() {
    session_start();
    // Check if email and password keys exist in the POST array
    if (!isset($_POST['email'], $_POST['password'])) {
        http_response_code(400);
        return json_encode(array(
            'message' => 'Request is missing.'
        ));
    }

    // Fetch POST data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Recaptcha System
    if(RECAPTCHA_ENABLED === true){
        $recaptcha_code = $_POST['recaptcha_response'];
        $remote_ip = $_SERVER['REMOTE_ADDR'];

        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = array('secret' => RECAPTCHA_SECRET_KEY, 'response' => $recaptcha_code, 'remoteip' => $remote_ip);
        
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        $json = json_decode($result);
        
        if (!$json->success) {
            http_response_code(401);
            return json_encode(array(
                'message' => 'RECAPTCHA error.'
            ));
        }
    }

    try {
      $stmt = $this->conn->prepare('SELECT StudentData.StudentID, StudentData.FullName as fname, StudentData.Birthday, StudentData.Gender, StudentData.Address, StudentData.Status, StudentData.Semester, users.id, users.username, users.password, users.type FROM StudentData LEFT JOIN users ON StudentData.StudentID = users.std_id WHERE users.email = :email');
      $stmt->execute(array('email' => $email));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$user) {
        http_response_code(401);
        return json_encode(array('message' => 'Invalid username or password'));
      }
      
      if (password_verify($password, $user['password'])) {

        // Check user type
        if($user['type'] == 'unverified'){
            http_response_code(401);
            return json_encode(array('message' => 'Account not yet verified.'));
        }

        // If user is admin, set default panel to normal
        $_SESSION['user']['panel'] = 'default';

        // Store user data in session
        foreach ($user as $variable_name => $variable_value) {
          if ($variable_name !== 'password') {
            $_SESSION['user'][$variable_name] = $variable_value;
          }
        }

        $token = generateToken($user['id']);
         $_SESSION['user']['token'] = $token;
        $response = array(
            'token' => $token
        );

        $session_id = session_id();
        setcookie('PHPSESSID', $session_id, time() + (7 * 24 * 60 * 60), '/');
        
        return json_encode($response);
      }

        http_response_code(401);
        return json_encode(array('message' => 'Invalid username or password'));

    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }   
  }
  
  public function switch(){
    session_start();

    $panel = $_SESSION['user']['panel'];

    // User is not logged.
    if (!isset($panel)) {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Unauthorized.'
        ));
    }

    http_response_code(200);

    if($panel === 'admin'){
        $_SESSION['user']['panel'] = 'default';
        return json_encode(array('message' => 'Panel switched to Default successfully. Panel: '.$panel));
    } else {
        $_SESSION['user']['panel'] = 'admin';
        return json_encode(array('message' => 'Panel switched to Admin successfully. Panel:'.$panel));
    }
  }

  // logout user and destroy session
  public function logout() {
    session_start();

    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    
    // Destroy the session
    session_destroy();
    
    // Redirect to the login page
    return true;
    exit;
  }
  
  // register user
  public function register() {

    if (!isset($_POST['studentID'], $_POST['email'], $_POST['birthdate'], $_POST['username'])) {
        http_response_code(400);
        return json_encode(array(
            'message' => 'Request is missing.'
        ));
    }

    // Fetch POST data
    $studentID = $_POST['studentID'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $username = $_POST['username'];

    // Recaptcha System
    if(RECAPTCHA_ENABLED === true){
        $recaptcha_code = $_POST['recaptcha_response'];
        $remote_ip = $_SERVER['REMOTE_ADDR'];

        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = array('secret' => RECAPTCHA_SECRET_KEY, 'response' => $recaptcha_code, 'remoteip' => $remote_ip);
        
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        $json = json_decode($result);
        
        if (!$json->success) {
            http_response_code(401);
            return json_encode(array(
                'message' => 'RECAPTCHA error.'
            ));
        }
    }

    try {
        $stmt = $this->conn->prepare('SELECT CASE 
                                      WHEN EXISTS (SELECT * FROM StudentData WHERE StudentID = :studentID AND Birthday = :birthdate)
                                      THEN 
                                        CASE 
                                          WHEN EXISTS (SELECT * FROM users WHERE email = :email) 
                                          THEN 1 
                                          WHEN EXISTS (SELECT * FROM users WHERE std_id = :studentID) 
                                          THEN 2 
                                          WHEN EXISTS (SELECT * FROM users WHERE username = :username)
                                          THEN 3
                                          ELSE 4 
                                        END 
                                      ELSE 
                                        0 
                                      END AS result;'); 
        $stmt->execute(array('studentID' => $studentID, ':email' => $email, ':birthdate' => $birthdate, ':username' => $username));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        switch($result['result']){
            case 0:
                http_response_code(401);
                return json_encode(array('message' => 'Student ID and/or Birthdate cannot be found.', 'code' => 0));
                break;

            case 1:
                http_response_code(401);
                return json_encode(array('message' => 'Email is already registered.', 'code' => 1));
                break;

            case 2:
                http_response_code(401);
                return json_encode(array('message' => 'Student already registered.', 'code' => 2));
                break;

            case 3:
                http_response_code(401);
                return json_encode(array('message' => 'Username is already taken.', 'code' => 3));
                break;

            case 4:
                // Generate a code
                $code = substr(uniqid(), 0, 15);

                // All conditions are good, student can be registered
                $stmt = $this->conn->prepare('INSERT INTO users (std_id, email, username, password, type, verification_code) VALUES (:std_id, :email, :username, "verified_first", "unverified", :verification_code)');
                $stmt->execute(array(':std_id' => $studentID, ':email' => $email, ':username' => $username, ':verification_code' => $code));

                // Send verification email
                $to = $email;
                $subject = 'Verify account - Cecilian Student Portal';
                $verification_link = SITE_URL . '/account/verify.php?code=' . $code;
                $message = sprintf(VERIFICATION_TEMPLATE, $username, $verification_link, $verification_link);
                send_email($to, $subject, $message);

                http_response_code(200);
                return json_encode(array('message' => 'Success. Kindly check your email to verify account.', 'code' => 4));
                break;
        }
    } catch (PDOException $e) {
        http_response_code(500);
        return json_encode(array('message' => 'Database error: ' . $e->getMessage()));
    }
}

  public function getUsers() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $user_id = $_SESSION['user']['id'];
    $user_type = $_SESSION['user']['type'];
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

  public function admin_resendVerification() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $target_id = $_POST['id'];

    $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'guest';
    $user_type = isset($_SESSION['user']['type']) ? $_SESSION['user']['type'] : 'guest';
    $user_token = isset($_SESSION['user']['token']) ? $_SESSION['user']['token'] : 'guest';

    if (empty($_SESSION['user']['token']) || $_SESSION['user']['token'] === 'guest') {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Logging in is required to proceed.'
        ));
    }

    // Check if user_type is eligible with this request.
    if ($user_type !== 'admin' && $user_type !== 'moderator' && $user_type !== 'officer') {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Unauthorized Request'
        ));
    }

    // Check if token is still valid/expired.
    $decoded_token = verifyToken($user_token);
    if($decoded_token['user_id'] != $user_id){
        http_response_code(401);
        return json_encode(array(
            'message' => 'Token already expired.'
        ));
    }

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
            // Send verification email
            $to = $users['email'];
            $subject = 'Verify account - Cecilian Student Portal';
            $verification_link = SITE_URL . '/account/verify.php?code=' . $users['verification_code'];
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

  public function admin_delete()
  {
      if (session_status() !== PHP_SESSION_ACTIVE) {
          session_start();
      }
  
      $target_id = $_POST['user_id'];
  
      $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'guest';
      $user_type = isset($_SESSION['user']['type']) ? $_SESSION['user']['type'] : 'guest';
      $user_token = isset($_SESSION['user']['token']) ? $_SESSION['user']['token'] : 'guest';
  
      if (empty($_SESSION['user']['token']) || $_SESSION['user']['token'] === 'guest') {
          http_response_code(401);
          return json_encode(array(
              'message' => 'Logging in is required to proceed.'
          ));
      }
  
      // Check if user_type is eligible with this request.
      if ($user_type !== 'admin') {
          http_response_code(401);
          return json_encode(array(
              'message' => 'Unauthorized Request'
          ));
      }
  
      // Check if token is still valid/expired.
      $decoded_token = verifyToken($user_token);
      if ($decoded_token['user_id'] != $user_id) {
          http_response_code(401);
          return json_encode(array(
              'message' => 'Token already expired.'
          ));
      }
  
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

  public function admin_update() {
      session_start();
    
      // Check if user is logged in as an admin
      if ($_SESSION['user']['type'] !== 'admin' && $_SESSION['user']['type'] !== 'moderator') {
        http_response_code(401);
        return json_encode(array(
          'message' => 'Unauthorized access'
        ));
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

  public function admin_nameid_query() {

      // Check if session already started
      if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
      }

      // Check if user is logged
      if ($_SESSION['user']['token'] !== null) {
        $user_token = $_SESSION['user']['token'];
        $user_id = $_SESSION['user']['id'];

        $decoded_token = verifyToken($user_token);

        if($decoded_token['user_id'] != $user_id){
            http_response_code(401);
            return json_encode(array(
                'message' => 'Session already expired.'
            ));
        }
      }

      // Fetch live data
      $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
      $stmt->execute(array(':id' => $_SESSION['user']['id']));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
      // Check if user is logged in as an admin
      if ($user['type'] !== 'admin') {
        http_response_code(401);
        return json_encode(array(
          'message' => 'Unauthorized access'
        ));
      }
    
      if ($user['type'] === 'admin') {
          // Check if required parameters are provided
          if (!isset($_POST['query'])) {
            http_response_code(400);
            return json_encode(array(
              'message' => 'Missing required parameters'
            ));
          }
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

  public function admin_create() {

      // Check if session already started
      if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
      }

      // Check if user is logged
      if ($_SESSION['user']['token'] !== null) {
        $user_token = $_SESSION['user']['token'];
        $user_id = $_SESSION['user']['id'];

        $decoded_token = verifyToken($user_token);

        if($decoded_token['user_id'] != $user_id){
            http_response_code(401);
            return json_encode(array(
                'message' => 'Session already expired.'
            ));
        }
      }

      // Fetch live data
      $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
      $stmt->execute(array(':id' => $_SESSION['user']['id']));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
      // Check if user is logged in as an admin
      if ($user['type'] !== 'admin') {
        http_response_code(401);
        return json_encode(array(
          'message' => 'Unauthorized access'
        ));
      }
    
      if ($user['type'] === 'admin') {
          // Check if required parameters are provided
          if (!isset($_POST['std_id']) && !isset($_POST['f_name']) && !isset($_POST['username']) && !isset($_POST['email']) && !isset($_POST['password']) && !isset($_POST['type'])) {
              http_response_code(400);
              return json_encode(array(
                  'message' => 'Missing required parameters'
              ));
          }

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

          
          $code = substr(uniqid(), 0, 15);

          // All conditions are good, student can be registered
          $stmt3 = $this->conn->prepare('INSERT INTO users (std_id, email, username, password, type, verification_code) VALUES (:std_id, :email, :username, :password, :type, :verification_code)');
          $stmt3->execute(array(':std_id' => $student_id, ':email' => $user_email, ':username' => $user_uname, 'password' => $user_pass, ':type' => $user_type , ':verification_code' => $code));

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

  
}

?>