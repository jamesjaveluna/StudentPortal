<?php
require_once('config.php');

function connect() {
  try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
  }
}

function getUserByUsernameAndPassword($username, $password) {
    try {
        $pdo = connect();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(array('username' => $username));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return false;
        }
        
        if (password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        exit();
    }
}

function hashPassword($password) {
    $options = [
        'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
        'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
        'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
    ];
    
    return password_hash($password, PASSWORD_ARGON2I, $options);
}
?>
