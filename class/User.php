<?php

require_once 'Database.php';
require_once 'config/jwt.php';

class User {
  
  // database connection
  private $conn;
  
  // constructor with $db as database connection
  public function __construct() {
    $database = new Database();
    $this->conn = $database->getConnection();
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

    try {
      $stmt = $this->conn->prepare('SELECT id, std_id, username, email, type, fname, birthdate, gender, address, status, password FROM users WHERE email = :email');
      $stmt->execute(array('email' => $email));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$user) {
        http_response_code(401);
        return json_encode(array('message' => 'Invalid username or password'));
      }
      
      if (password_verify($password, $user['password'])) {
        // Store user data in session
        foreach ($user as $variable_name => $variable_value) {
          if ($variable_name !== 'password') {
            $_SESSION['user'][$variable_name] = $variable_value;
          }
        }

        $token = generateToken($user['id']);

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
    extract($_POST);

    // your registration logic here
  }
  
  // fetch user data and store in session
  public function fetchUserData($user_id) {
    try {
      $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
      $stmt->bindParam(':id', $user_id);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$user) {
        return false;
      }
      
      // Store user data in session
      foreach ($user as $variable_name => $variable_value) {
        $_SESSION['user'][$variable_name] = $variable_value;
      }
      
      return $user;
    } catch (PDOException $e) {
      echo 'Error: ' . $e->getMessage();
      exit();
    }
  }

  // update user data
  public function updateUserData() {
    extract($_POST);

    // your user data update logic here
  }
  
  // change user password
  public function changePassword() {
    extract($_POST);

    // your change password logic here
  }
  
}

?>