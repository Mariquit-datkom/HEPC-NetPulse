<?php
    require_once 'dbConfig.php';
    session_start();

    $username = $_SESSION['username'];
    $sql = "UPDATE users SET ping = :ping, status = :status WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ping' => '0', 'status' => 'OFF' ,'username' => $username]);

    session_unset();
    session_destroy();
?>

<script>
    sessionStorage.removeItem('hasLoaded');
    sessionStorage.removeItem('isFullscreen');
    sessionStorage.removeItem('ipStatusRegistry');
    window.location.href = "logIn.php";
</script>