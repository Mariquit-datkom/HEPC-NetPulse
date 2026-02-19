<?php
    require_once 'x-head.php'; 

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Desktops</title>
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            
        </div>
    </div>
</body>
</html>