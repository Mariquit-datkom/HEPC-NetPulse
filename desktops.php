<?php
    require_once 'x-head.php'; 
    session_start();

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $filename = "assets/docs/desktops.txt";
    $importantIp = [];
    $otherAddresses = [];

    if (file_exists($filename)) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $currentSection = '';
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Detect Section Labels
            if (strpos($line, '-- Important --') !== false) {
                $currentSection = 'important';
                continue;
            } elseif (strpos($line, '-- Other Addresses --') !== false) {
                $currentSection = 'other';
                continue;
            }

            // Check if line contains a valid IP address pattern
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $line)) {
                if ($currentSection === 'important') {
                    $importantIp[] = $line;
                } elseif ($currentSection === 'other') {
                    $otherAddresses[] = $line;
                }
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
    <link rel="stylesheet" href="css/desktops.css">
    <link rel="stylesheet" href="css/loading.css">
    <title>Desktops</title>
</head>
<body>
    <div class="main-container">
        <?php include 'loading.php'; ?>
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="top-shelf">
                <div class="header-container">
                    <h2 class="header">Important Desktops</h2>
                </div>
                <div class="item-container">
                    <?php
                        $pings = isset($_SESSION['pings']) ? $_SESSION['pings'] : [];

                        foreach ($importantIp as $index => $ip):           
                    ?>
                    <div class="shelf-item content-container" data-ip="<?php echo htmlspecialchars($ip) ?>">
                        <span class="display-item"><i class="fa fa-desktop status-grey"></i><?php echo "$ip" ?></span>
                        <span class="display-ping">( -- )</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mid-shelf">
                <div class="header-container">
                    <h2 class="header">Other Desktops</h2>
                </div>
                <div class="search-row">
                    <input type="text" class="search-bar" id="search-bar" placeholder="Search.." autocomplete="off">
                    <button class="search-btn"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <div class="bottom-shelf">
                <div class="bottom-shelf-item">
                    <span><i class="fa fa-desktop status-green"></i> = Excellent Signal</span>
                </div>
                <div class="bottom-shelf-item">
                    <span><i class="fa fa-desktop status-yellow"></i> = Good Signal</span>
                </div>
                <div class="bottom-shelf-item">
                    <span><i class="fa fa-desktop status-red"></i> = Poor Signal</span>
                </div>
                <div class="bottom-shelf-item">
                    <span><i class="fa fa-desktop status-grey"></i> = Unresponsive</span>
                </div>
            </div>            
        </div>
    </div>

    <script> const currentPage = <?php echo json_encode($currentPage); ?>; </script>
    <script src="js/loading.js"></script>
    <script src="js/statusChecker.js"></script>
    <script src="js/search.js"></script>
</body>
</html>