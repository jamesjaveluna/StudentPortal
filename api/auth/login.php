<?php
// Include jwt.php file
require_once('jwt.php');

// Mock login credentials
$username = 'johndoe';
$password = 'password';

header('Content-Type: application/json');

// Check if username and password are correct
if ($username === 'johndoe' && $password === 'password') {
  // Generate JWT token with user ID
  $token = generateToken(123);

  // Return token as response
  echo json_encode(array('token' => $token));
} else {
  // Return error message
  http_response_code(401);
  echo json_encode(array('message' => 'Invalid username or password'));
}
?>
