<?php
$password = 'test'; // Replace with your plain-text password
$hashedPassword = password_hash($password, PASSWORD_ARGON2I);
echo $hashedPassword;

?>