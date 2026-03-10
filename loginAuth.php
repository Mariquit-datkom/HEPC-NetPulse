<?php

    require_once 'dbConfig.php'; // db config
    session_start(); // session fetch

    // Form Submission Authentication
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        // Checks if password is correct
        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['username'] = $user['username'];
            $now = time();

            $sql = "UPDATE users SET ping = :ping WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['ping' => $now ,'username' => $username]);

            header("Location: dashboard.php");
            exit();
            
        } else {
            $_SESSION['error'] = "<p style='color: red; font-size: 13px; font-family: Arial; margin-top: 5px;'> Invalid username or password. Please try again. </p>";
            header("Location: logIn.php");
            exit();
        }
    }
?>