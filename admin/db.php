<?php
// db.php - adjust credentials for your environment
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'activity1';
$port = 3306;

$sql = @new mysqli($host, $user, $pass, $db, $port);
if ($sql->connect_errno) {
  http_response_code(500);
  die("Database connection failed: " . htmlspecialchars($sql->connect_error));
}
$sql->set_charset('utf8mb4');
?>