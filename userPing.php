<?php
require_once 'dbConfig.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $now = time();
    
    $sql = "UPDATE users SET ping = :ping, status = :status WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ping' => $now, 'status' => 'ON', 'username' => $username]);
    
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(401);
}
?>