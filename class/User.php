<?php

require_once 'config/jwt.php';
require_once 'config/config.php';
require_once 'Database.php';
require_once 'Mailer.php';
require_once __DIR__.'./../assets/utility.php';


class User {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
  }

  // Requesting for request Password
  public function forgot_password() {
    session_start();

    if (!isset($_POST['email'])) {
        http_response_code(400);
        return json_encode(array(
            'message' => 'Request is missing.'
        ));
    }

    // Fetch POST data
    $email = $_POST['email'];

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
      $stmt = $this->conn->prepare('SELECT user.std_id, user.email, user.username, user.type FROM users user WHERE user.email = :email');
      $stmt->execute(array(':email' => $email));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$user) {
        http_response_code(401);
        return json_encode(array('message' => 'Unable to find user.'));
      }

        if($user['type'] !== 'unverified'){
            $code = substr(uniqid(), 0, 15);

            $username = $user['username'];

            // All conditions are good, student can be registered
            $stmt = $this->conn->prepare('UPDATE users SET token_code = :code WHERE email = :email');
            $stmt->execute(array(':code' => $code, ':email' => $email));

            // Send verification email
            $to = $email;
            $subject = 'Change Password - Cecilian Student Portal';
            $verification_link = SITE_URL . '/account/changepass?code=' . $code;
            $message = sprintf(CHANGEPASS_TEMPLATE, $username, $verification_link, $verification_link);
            send_email($to, $subject, $message);

            http_response_code(200);
            return json_encode(array('message' => 'Success, kindly check email.'));
        }

        http_response_code(401);
        return json_encode(array('message' => 'Verify account first.'));

    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }  
  }

  // The process of changing password
  public function changepass() {
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
    //SELECT StudentData.StudentID, StudentData.FullName as fname, StudentData.Birthday, StudentData.Gender, StudentData.Address, StudentData.Status, StudentData.Semester, StudentData.YearLevel, StudentData.Section, StudentData.Major, StudentData.Course, StudentData.Scholarship, StudentData.SchoolYear as SY, users.id, users.avatar, users.username, users.email, users.password, users.type FROM StudentData LEFT JOIN users ON StudentData.StudentID = users.std_id WHERE users.email = :email
      $stmt = $this->conn->prepare('SELECT StudentData.StudentID, StudentData.FullName as fname, StudentData.Birthday, StudentData.Gender, StudentData.Address, StudentData.Status, StudentData.Semester, StudentData.YearLevel, StudentData.Section, StudentData.Major, StudentData.Course, StudentData.Scholarship, StudentData.SchoolYear as SY, users.id, users.avatar, users.username, users.email, users.password, users.type, users.verification_code FROM StudentData LEFT JOIN users ON StudentData.StudentID = users.std_id WHERE users.token_code = :code AND users.type != "unverified"');
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
        $update_stmt = $this->conn->prepare('UPDATE users SET password = :password, token_code = "resetted" WHERE token_code = :code');
        $update_stmt->execute(array(':password' => $hashed_password, ':code' => $code));

        $response = array(
            'message' => 'Password changed successfully'
        );

        return json_encode($response);
      }

        http_response_code(401);
        return json_encode(array('message' => 'Password do not match.'));

    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }  
  }

  // verify account
  public function resend_email() {
    session_start();

    if (!isset($_POST['student_id'], $_POST['email'])) {
        http_response_code(400);
        return json_encode(array(
            'message' => 'Request is missing.'
        ));
    }

    // Fetch POST data
    $std_id = $_POST['student_id'];
    $email = $_POST['email'];

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
      $stmt = $this->conn->prepare('SELECT user.std_id, user.email, user.username, user.type FROM users user WHERE user.std_id = :std_id AND user.email = :email');
      $stmt->execute(array(':std_id' => $std_id, ':email' => $email));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$user) {
        http_response_code(401);
        return json_encode(array('message' => 'Unable to find user.'));
      }

      if($user['type'] !== 'unverified'){
        http_response_code(401);
        return json_encode(array('message' => 'User must be unverified.'));
      }

      if($user['type'] === 'unverified'){
      
        $code = substr(uniqid(), 0, 15);

        $username = $user['username'];

        // All conditions are good, student can be registered
        $stmt = $this->conn->prepare('UPDATE users SET verification_code = :code WHERE std_id = :std_id AND email = :email');
        $stmt->execute(array(':code' => $code, ':std_id' => $std_id, ':email' => $email));

        // Send verification email
        $to = $email;
        $subject = 'Verify account - Cecilian Student Portal';
        $verification_link = SITE_URL . '/account/verify?code=' . $code;
        $message = sprintf(VERIFICATION_TEMPLATE, $username, $verification_link, $verification_link);
        send_email($to, $subject, $message);

        http_response_code(200);
        return json_encode(array('message' => 'Verification sent successfully.'));
      }

    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }  
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
    //SELECT StudentData.StudentID, StudentData.FullName as fname, StudentData.Birthday, StudentData.Gender, StudentData.Address, StudentData.Status, StudentData.Semester, StudentData.YearLevel, StudentData.Section, StudentData.Major, StudentData.Course, StudentData.Scholarship, StudentData.SchoolYear as SY, users.id, users.avatar, users.username, users.email, users.password, users.type FROM StudentData LEFT JOIN users ON StudentData.StudentID = users.std_id WHERE users.email = :email
      $stmt = $this->conn->prepare('SELECT StudentData.StudentID, StudentData.FullName as fname, StudentData.Birthday, StudentData.Gender, StudentData.Address, StudentData.Status, StudentData.Semester, StudentData.YearLevel, StudentData.Section, StudentData.Major, StudentData.Course, StudentData.Scholarship, StudentData.SchoolYear as SY, users.id, users.avatar, users.username, users.email, users.password, users.type, users.verification_code FROM StudentData LEFT JOIN users ON StudentData.StudentID = users.std_id WHERE users.verification_code = :code AND users.type = "unverified"');
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
        $update_stmt = $this->conn->prepare('UPDATE users SET password = :password, type = "member", verification_code = "resetted" WHERE verification_code = :code');
        $update_stmt->execute(array(':password' => $hashed_password, ':code' => $code));


        // Store user data in session
        foreach ($user as $variable_name => $variable_value) {
          if ($variable_name !== 'password' || $variable_name !== 'verification_code') {
            $_SESSION['user'][$variable_name] = $variable_value;
          }
        }

        // If user is admin, set default panel to normal
        $_SESSION['user']['panel'] = 'default';

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
    // 10001 - Recaptcha Error
    // 10002 - Email or password is wrong

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
                'message' => 'RECAPTCHA error.',
                'code' => 10001
            ));
        }
    }

    try {
      $stmt = $this->conn->prepare('SELECT StudentData.StudentID, StudentData.FullName as fname, StudentData.Birthday, StudentData.Gender, StudentData.Address, StudentData.Status, StudentData.Semester, StudentData.YearLevel, StudentData.Section, StudentData.Major, StudentData.Course, StudentData.Scholarship, StudentData.SchoolYear as SY, users.id, users.std_id, users.avatar, users.username, users.email, users.password, users.type, users.permission FROM StudentData LEFT JOIN users ON StudentData.StudentID = users.std_id WHERE users.email = :email');
      $stmt->execute(array('email' => $email));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$user) {
        http_response_code(401);
        return json_encode(array('message' => 'Invalid email and/or password', 'code' => 10002));
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
        return json_encode(array('message' => 'Invalid email and/or password', 'code' => 10002));

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
                $verification_link = SITE_URL . '/account/verify?code=' . $code;
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

  public function getUser($target_id) {
    $allowedUserType = array('admin', 'moderator', 'officer', 'member', 'faculty');
    $allowedPermission = null;
    $section = 'bypass'; //Bypass: No permission needed

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
      $stmt = $this->conn->prepare('SELECT sd.*, us.id, us.std_id, us.username, us.email, us.type, us.avatar, us.permission, DATE_FORMAT(us.createdDate, "%M %d, %Y") as createdDate FROM `users` us LEFT JOIN StudentData sd ON sd.StudentID = us.std_id WHERE us.id = :id');
      $stmt->bindParam(':id', $target_id, PDO::PARAM_INT); // Bind the ID parameter to the query
      $stmt->execute();
      $students = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$students) {
        http_response_code(401);
        return json_encode(array(
            'message' => 'Failed to fetch Users Data.',
            'code' => 10003,
            'debug' => 'Data Binded: '.$target_id
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

  /* SETTINGS SECTION */
  public function settingsChangePass() {
      // 10000 = Success
      // 10001 = Session already expired
      // 10002 = Not authorized
      // 10003 = Password do not match
      // 10004 = Old password is wrong.
      $allowedUserType = array('admin', 'moderator', 'officer', 'member');
      $allowedPermission = 'change_password';
      $section = 'user_panel';
  
      $utility = new Utility();
      switch ($utility->checkPermission($allowedUserType, $section, $allowedPermission)) {
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
                  'message' => 'Not authorized to view this data.',
                  'code' => 10002,
                  'debug' => 'Permission required: <code>' . $allowedPermission . '</code>'
              ));
              break;
      }
  
      if (!isset($_POST['oldPassword'], $_POST['newPassword'], $_POST['retypePassword'])) {
          http_response_code(400);
          return json_encode(array(
              'message' => 'Request is missing.'
          ));
      }
  
      // Fetch POST data
      $oldPassword = $_POST['oldPassword'];
      $newPassword = $_POST['newPassword'];
      $retypePassword = $_POST['retypePassword'];
  
      $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

      if(empty($oldPassword) || empty($newPassword) || empty($retypePassword)){
        http_response_code(400);
          return json_encode(array(
              'message' => 'Cannot proceed with an empty values.'
          ));
      }
  
      try {
          // Check if the user exists and retrieve the user's password
          $stmt = $this->conn->prepare('SELECT * FROM `users` WHERE `id` = :id');
          $stmt->execute(array(':id' => $user_id));
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
          if (!$user) {
              http_response_code(401);
              return json_encode(array('message' => 'Sorry, we can\'t find you in the system.'));
          }
          
          // Check if the new password matches the retype password
          if ($newPassword !== $retypePassword) {
              http_response_code(401);
              return json_encode(array('message' => 'New password and retype password do not match.', 'code' => 10003));
          }

          // Verify if the old password matches the user's current password
          if (!password_verify($oldPassword, $user['password'])) {
              // Increment the password attempts
              $_SESSION['password_attempts'] = isset($_SESSION['password_attempts']) ? $_SESSION['password_attempts'] + 1 : 2;
  
              // Check if the password attempts have reached the limit
              if ($_SESSION['password_attempts'] >= 3) {
                  // Logout the user and clear session data
                  session_unset();
                  session_destroy();
  
                  http_response_code(403);
                  return json_encode(array('message' => 'You have exceeded the maximum password attempts. You are now logged out.', 'code' => 10001));
              }
  
              http_response_code(401);
              return json_encode(array('message' => 'Old password is incorrect. (Attempt: ' . $_SESSION['password_attempts'] .'/3)', 'code' => 10004));
          }
  
          // Hash the new password
          $hashed_password = password_hash($newPassword, PASSWORD_ARGON2I);
          
          // Reset the password attempts upon successful password entry
          $_SESSION['password_attempts'] = 0;

          // Update user with the new hashed password
          $update_stmt = $this->conn->prepare('UPDATE users SET password = :password WHERE id = :id');
          $update_stmt->execute(array(':password' => $hashed_password, ':id' => $user_id));
  
          $response = array(
              'message' => 'Password changed successfully',
              'code' => 10000
          );
  
          return json_encode($response);
      } catch (PDOException $e) {
          echo 'Error: ' . $e->getMessage();
          exit();
      }
  }

}

?>