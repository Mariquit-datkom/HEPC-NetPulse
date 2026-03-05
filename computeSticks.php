<?php
    require_once 'x-head.php'; 
    session_start();

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/biometrics.css">
    <link rel="stylesheet" href="css/loading.css">
    <title>Compute Sticks</title>
</head>
<body>
    <div class="main-container">
        <?php include 'loading.php'; ?>
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            
        </div>
    </div>

    <script src="js/loading.js"></script>
</body>
</html>