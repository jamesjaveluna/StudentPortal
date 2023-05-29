<?php

$page_title = "Index";
$return_url = $_SERVER['REQUEST_URI'];

require_once('./class/config/config.php');

if(GEN_MAINTENANCE === true){
  include('./pages/maintenance.php');
  exit();
}

ob_start();

session_start();

// Check if session token is empty
if (empty($_SESSION['user']['token'])) {
  // Redirect to login page
  header("Location: ./account/login.php?return_url=" . urlencode($return_url));
  exit();
}

// Switch to admin panel
if($_SESSION['user']['panel'] === 'admin'){
  header("Location: ./../admin/dashboard");
  exit();
} else {
  header("Location: ./../dashboard");
  exit();
}


?>
