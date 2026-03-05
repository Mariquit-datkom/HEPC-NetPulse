<?php
    require_once 'x-head.php'; 
    session_start();

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $filename = "assets/docs/computers.txt";
    $laptops = [];

    if (file_exists($filename)) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $currentSection = '';
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Detect Section Labels
            if (strpos($line, '-- Laptops --') !== false) {
                $currentSection = 'laptops';
                continue;
            }

            // Check if line contains a valid IP address pattern
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $line) && $currentSection === 'laptops') $laptops[] = $line;
        }
    }

    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/laptops.css">
    <link rel="stylesheet" href="css/loading.css">
    <title>Laptops</title>
</head>
<body>
    <div class="main-container">
        <?php include 'loading.php'; ?>
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="shelf">
                <div class="header-container">
                    <h2 class="header">Laptops</h2>
                </div>
                <div class="search-row">
                    <input type="text" class="search-bar" id="search-bar" placeholder="Search..">
                    <button class="search-btn"><i class="fa fa-search"></i></button>
                </div>
                <div class="scrollable-area">
                   <?php
                        $pings = isset($_SESSION['pings']) ? $_SESSION['pings'] : [];

                        foreach ($laptops as $index => $ip):           
                    ?>
                    <div class="shelf-item content-container" data-ip="<?php echo htmlspecialchars($ip) ?>">
                        <span class="display-item"><i class="fa fa-laptop status-grey"></i><?php echo "$ip" ?></span>
                        <span class="display-ping">( -- )</span>
                    </div>
                    <?php endforeach; ?>
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
                    <span><i class="fa fa-desktop status-grey"></i> = Timed Out / Error</span>
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