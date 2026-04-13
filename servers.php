<?php
    require_once 'x-head.php'; 
    require_once 'userHeartbeatChecker.php'; 

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $filename = "assets/docs/addresses/ipAddresses.txt";
    $servers = [];

    if (file_exists($filename)) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $currentSection = '';
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Detect Section Labels
            if (strpos($line, '-- Servers --') !== false) {
                $currentSection = 'servers';
                continue;
            } elseif (strpos($line, '--') === 0) {
                $currentSection = 'none'; 
                continue;
            }

            $parts = explode(' - ', $line, 2);
            $ip = trim($parts[0]);
            $name = isset($parts[1]) ? trim($parts[1]) : null;

            // Check if line contains a valid IP address pattern
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $ip)) {
                $entry = ['ip' => $ip, 'name' => $name];
                if ($currentSection === 'servers') $servers[] = $entry;
            }
        }
    }

    $currentPage = basename($_SERVER['PHP_SELF']);
    $allAddresses = $_SESSION['allAddresses'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/ipAddresses.css">
    <link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" href="css/mobile.css" media="screen and (max-width: 800px)">
    <title>Servers</title>

    <script>
        if (sessionStorage.getItem('isFullscreen') === 'true') {
            document.documentElement.classList.add('fullscreen-active');
        }
    </script>
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="top-shelf">
                <div class="header-container">
                    <h2 class="header">Servers</h2>
                </div>
                <div class="search-row">
                    <input type="text" class="search-bar" id="search-bar" placeholder="Search.." autocomplete="off" autocomplete="off">
                    <button class="search-btn"><i class="fa fa-search"></i></button>
                </div>
                <div class="item-container">
                    <div class="no-results"> No results found. </div>
                    <?php
                        $pings = isset($_SESSION['pings']) ? $_SESSION['pings'] : [];

                        foreach ($servers as $index => $item):           
                    ?>
                    <div class="shelf-item content-container" data-ip="<?php echo htmlspecialchars($item['ip']) ?>">
                        <span class="display-item">
                            <?php if ($item['name']): ?>
                                <div class="name-row">
                                    <i class="far fa-server status-grey"></i>
                                    <span class="name-text"><strong><?php echo htmlspecialchars($item['name']); ?></strong></span>
                                </div>                                
                                <small class="ip-text"><?php echo htmlspecialchars($item['ip']); ?></small>
                            <?php else: ?>
                                <div class="name-row">
                                    <i class="far fa-server status-grey"></i>
                                    <?php echo htmlspecialchars($item['ip']); ?>
                                </div>
                            <?php endif; ?>
                        </span>
                        <span class="display-ping">( -- )</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php include 'statusLegend.php'; ?>  
        </div>
    </div>
    <?php include 'systemAlert.php'; ?>

    <script> const currentPage = <?php echo json_encode($currentPage); ?>; </script>
    <script> const allAddresses = <?php echo json_encode($allAddresses); ?>; </script>
    <script src="js/statusChecker.js"></script>
    <?php include 'scripts.php'; ?>
</body>
</html>