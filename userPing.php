<?php
require_once 'dbConfig.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $now = time();
    
    $stmt = $pdo->prepare("UPDATE users SET ping = :ping, status = :status WHERE username = :username");
    $stmt->execute(['ping' => $now, 'status' => 'ON', 'username' => $username]);

    $stmt = $pdo->prepare("UPDATE page_locks SET locked_at = :now WHERE username = :user");
    $stmt->execute(['now' => $now, 'user' => $username]);
    
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(401);
}
?>