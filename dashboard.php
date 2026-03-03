<?php
    require_once 'dbConfig.php'; 
    require_once 'x-head.php';
    session_start(); 

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $ipAddressTextFile = "assets/docs/ipAddresses.txt";
    $importantIp = [];
    if (file_exists($ipAddressTextFile)) {
        $lines = file($ipAddressTextFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentSection = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, '-- Important --') !== false) { 
                $currentSection = 'important'; 
                continue; 
            } elseif (strpos($line, '-- Other Addresses --') !== false) {
                $currentSection = 'other';
                break;
            }
            
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $line) && $currentSection === 'important') {
                $importantIp[] = $line;
            }
        }
    }

    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/loading.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <title>Dashboard</title>
</head>
<body>
    <?php include 'loading.php'; ?>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="desktop-pie">
                <p class="container-title">Desktops</p>
            </div>
            <div class="laptop-pie">
                <p class="container-title">Laptops</p>
            </div>
            <div class="ip-pie">
                <p class="container-title">IP Addresses</p>
            </div>
            <div class="latency-graph">
                <p class="container-title">Real-Time Network Latency (ms)</p>
                <div class="graph-container">
                    <canvas id="latency-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script> const importantIPs = <?php echo json_encode($importantIp); ?>; </script>
    <script src="js/latencyTracker.js"></script>
    <script src="js/loading.js"></script>
</body>
</html>