<?php
require_once 'dbConfig.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $now = time();
    
    try {
        $stmt = $pdo->prepare("SELECT ping FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $diff = $now - $user['ping'];

            if ($diff === null || $diff > 30) {
                session_unset();
                session_destroy();
                header("Location: logIn.php?reason=timeout");
                exit();
            }
        }
    } catch (PDOException $e) {
        error_log("Heartbeat Check Error: " . $e->getMessage());
    }
}
?>