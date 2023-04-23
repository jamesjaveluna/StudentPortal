<?php
require_once('config/config.php');

class Database {
  private $pdo;

  function __construct() {
    try {
      $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
      $this->pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
      $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
      exit;
    }
  }

  public function getConnection() {
    return $this->pdo;
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
