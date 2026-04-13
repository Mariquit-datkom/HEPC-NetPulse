<?php
    require_once 'x-head.php';
    require_once 'userHeartbeatChecker.php';

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $ipAddressTextFile = "assets/docs/addresses/ipAddresses.txt";
    $servers = [];
    $switches = [];
    $biometrics = [];
    $ipAddresses = [];
    if (file_exists($ipAddressTextFile)) {
        $lines = file($ipAddressTextFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentSection = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, '-- Servers --') !== false) {                 
                $currentSection = 'servers'; 
                continue; 
            } elseif (strpos($line, '-- Switches --') !== false) {
                $currentSection = 'switches';
                continue;
            } elseif (strpos($line, '-- Biometrics --') !== false) {
                $currentSection = 'biometrics';
                continue;
            }

            $parts = explode(' - ', $line, 2);
            $ip = trim($parts[0]);
            
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $ip)) {
                if ($currentSection === 'servers' && count($servers) < 5) $servers[] = $ip;
                elseif ($currentSection === 'switches' && count($switches) < 5) $switches[] = $ip;
                elseif ($currentSection === 'biometrics' && count($biometrics) < 5) $biometrics[] = $ip;

                $ipAddresses[] = $ip;
            }
            
            if(count($servers) >= 5 && count($switches) >= 5 && count($biometrics) >= 5) $currentSection = 'default';
        }
    }

    $priorityAddresses = array_merge($servers, $switches, $biometrics);

    $otherAddressTextFile = "assets/docs/addresses/otherCategories.txt";
    $otherAddresses = [];
    $allAddresses = [];
    if (file_exists($otherAddressTextFile)) {        
        $lines = file($otherAddressTextFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim(($line));
            $parts = explode(' - ', $line, 2);
            $ip = trim($parts[0]);
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $ip)) $otherAddresses[] = $ip;
        }
        $allAddresses = [...$ipAddresses, ...$otherAddresses];
    }

    $_SESSION['allAddresses'] = $allAddresses;

    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" href="css/mobile.css" media="screen and (max-width: 800px)">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        (function() {
            if (!sessionStorage.getItem('hasLoaded')) {
                var style = document.createElement('style');
                style.innerHTML = '#loading-screen { display: flex !important; }';
                document.head.appendChild(style);
            }
        })();

        if (sessionStorage.getItem('isFullscreen') === 'true') {
            document.documentElement.classList.add('fullscreen-active');
        }
    </script>
    
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
        <?php include 'systemAlert.php'; ?>
    </div>
    <script> const servers = <?php echo json_encode($servers); ?>; </script>
    <script> const switches = <?php echo json_encode($switches); ?>; </script>
    <script> const biometrics = <?php echo json_encode($biometrics); ?>; </script>  
    <script> const allAddresses = <?php echo json_encode($allAddresses); ?>; </script>
    <script> const priorityAddresses = <?php echo json_encode($priorityAddresses); ?>; </script>
    <script src="js/latencyTracker.js"></script>
    <script src="js/statusChecker.js"></script>
    <script src="js/loading.js"></script>
    <script src="js/userHeartbeat.js"></script>
    <?php include 'scripts.php'; ?>
</body>
</html>