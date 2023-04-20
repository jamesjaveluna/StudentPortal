<?php
require_once('../jwt.php');
require_once('../config.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
$token_prefix = 'Bearer ';

// Check if token is missing
if (!isset($headers['Authorization']) || empty(trim($headers['Authorization']))) {
  http_response_code(401);
  echo json_encode(array('message' => 'Token is missing'));
  exit;
}

// Get the token from the header
$token = trim($headers['Authorization']);
if (substr($token, 0, strlen($token_prefix)) === $token_prefix) {
  $token = substr($token, strlen($token_prefix));
}

// Verify the token
$decoded_token = verifyToken($token);

if (!$decoded_token) {
  http_response_code(401);
  echo json_encode(array('message' => 'Invalid token'));
  exit;
}

// Get the user ID from the token
$user_id = $decoded_token['user_id'];

// Return user data as response
echo json_encode($user_id);
?>
