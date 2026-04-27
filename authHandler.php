<?php

    require_once 'dbConfig.php'; // db config
    session_start(); // session fetch

    // Form Submission Authentication
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if ($_POST['action'] === 'register') {
            try {

                $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
                $checkStmt->execute(['username' => $username]);
                
                if ($checkStmt->fetchColumn() > 0) {
                    $_SESSION['error'] = "<p style='color: red; font-size: 14px; font-family: Arial; margin-top: 5px;'>That username is already taken. Please choose another.</p>";
                    header("Location: logIn.php");
                    exit();
                }

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (username, password, ping, status) VALUES (:username, :password, 0, 'OFF')");
                $stmt->execute(['username' => $username, 'password' => $hashedPassword]);

                $_SESSION['success'] = "<p style='color: green; font-size: 14px; font-family: Arial; margin-top: 5px;'> New account registered successfully! </p>";
                header("Location: logIn.php");
                exit();   
            } catch (PDOException $e) {
                $_SESSION['error'] = "<p style='color: red; font-size: 14px; font-family: Arial; margin-top: 5px;'>A database error occurred. Please try again later.</p>";
                header("Location: logIn.php");
                exit();
            } 

        } else {
            try {
                $sql = "SELECT * FROM users WHERE username = :username";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['username' => $username]);
                $user = $stmt->fetch();
                
                if (empty($_POST['username']) || empty($_POST['password'])) {
                    $_SESSION['error'] = "<p style='color: red; font-size: 14px; font-family: Arial; margin-top: 5px;'> Fill up all fields with necessary information. </p>";
                    header("Location: logIn.php");
                    exit();

                } else if (empty($user) || !password_verify($password, $user['password'])) {        
                    $_SESSION['error'] = "<p style='color: red; font-size: 14px; font-family: Arial; margin-top: 5px;'> Invalid username or password. Please try again. </p>";
                    header("Location: logIn.php");
                    exit();                

                } else if ($user['status'] !== 'OFF') { 
                    $_SESSION['error'] = "<p style='color: red; font-size: 14px; font-family: Arial; margin-top: 5px;'> User is still logged in another device. Logout and try again. </p>";
                    header("Location: logIn.php");
                    exit();  

                } else {
                    $_SESSION['username'] = $user['username'];
                    $now = time();

                    $sql = "UPDATE users SET ping = :ping, status = :status WHERE username = :username";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['ping' => $now, 'status' => 'ON', 'username' => $username]);

                    header("Location: dashboard.php");
                    exit();
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "<p style='color: red; font-size: 14px; font-family: Arial; margin-top: 5px;'>A database error occurred. Please try again later.</p>";
                header("Location: logIn.php");
                exit();
            }
        } 
    }
?>