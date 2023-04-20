<?php

require_once('../jwt.php');

header('Content-Type: application/json');

$user_id = getUserIdFromToken();

// Return user data as response
echo json_encode($user_id);
?>
