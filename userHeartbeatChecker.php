<?php
require_once 'dbConfig.php';
if (session_status() === PHP_SESSION_NONE) session_start();
    
$now = time();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    
    try {
        $stmt = $pdo->prepare("SELECT ping FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $diff = $now - $user['ping'];
            $lastSavedPing = $user['ping'];
            echo "<script>console.warn('DEBUG: Current: $now | DB: $lastSavedPing | Gap: $diff seconds');</script>";

            if ($diff === null || $diff > 30) {
                $sql = "UPDATE users SET ping = :ping, status = :status WHERE username = :username";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['ping' => '0', 'status' => 'OFF' ,'username' => $username]);

                session_unset();
                session_destroy();
                header("Location: logIn.php?reason=timeout");
                exit();
            }
        }
    } catch (PDOException $e) {
        error_log("Heartbeat Check Error: " . $e->getMessage());
    }
} else {
    $rowCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
    for ($i = 1; $i <= $rowCount ; $i++) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $i]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $username = $user['username'];
            $diff = $now - $user['ping'];
            $lastSavedPing = $user['ping'];
            echo "<script>console.warn('DEBUG: Current: $now | DB: $lastSavedPing | Gap: $diff seconds');</script>";

            if ($diff === null || $diff > 30) {
                $sql = "UPDATE users SET ping = :ping, status = :status WHERE username = :username";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['ping' => '0', 'status' => 'OFF' ,'username' => $username]);

                session_unset();
                continue;
            }
        }
    }
}
?>