<?php
// Include jwt.php and database.php files
require_once('../jwt.php');
require_once('../database.php');

// Get the POST data
$username = $_POST['username'];
$password = $_POST['password'];

header('Content-Type: application/json');

// Check if username and password are correct
$user = getUserByUsernameAndPassword($username, $password);

// Get user by username and password
$user = getUserByUsernameAndPassword($username, $password);

if ($user) {
    // Password is correct
    // Generate JWT token with user ID
    $token = generateToken($user['id']);

    $response = array(
        'token' => $token,
        'message' => 'Login Successful'
    );

    // Return token as response
    echo json_encode($response);
} else {
    // Return error message
    http_response_code(401);
    echo json_encode(array('message' => 'Invalid username or password'));
}
?>
