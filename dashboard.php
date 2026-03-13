<?php
    require_once 'x-head.php';
    require_once 'userHeartbeatChecker.php';

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $currentPage = basename($_SERVER['PHP_SELF']);

    include_once 'generateIp.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" href="css/mobile.css" media="screen and (max-width: 800px)">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <title>Dashboard</title>
</head>
<body>
    <?php include 'loading.php'; ?>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="biometrics-latency-graph">
                <p class="container-title">Biometrics Real-Time Network Latency (ms)</p>  
                <div class="graph-container">
                    <canvas id="biometric-latency-chart"></canvas>
                </div>          
            </div>
            <div class="switch-latency-graph">
                <p class="container-title">Switch Real-Time Network Latency (ms)</p>
                <div class="graph-container">
                    <canvas id="switch-latency-chart"></canvas>
                </div>
            </div>
            <div class="servers-latency-graph">
                <p class="container-title">Servers Real-Time Network Latency (ms)</p>
                <div class="graph-container">
                    <canvas id="server-latency-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php include 'systemAlert.php'; ?>
    <?php include 'ipToJs.php'; ?>

    <script src="js/latencyTracker.js"></script>
    <script src="js/statusChecker.js"></script>
    <?php include 'scripts.php'; ?>
</body>
</html>