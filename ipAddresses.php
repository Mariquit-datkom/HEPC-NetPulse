<?php
    require_once 'dbConfig.php';
    require_once 'x-head.php'; 

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    $filename = "assets/docs/ipAddresses.txt";
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
    <link rel="stylesheet" href="css/ipAddresses.css">
    <link rel="stylesheet" href="css/loading.css">
    <title>IP Addresses</title>
</head>
<body>
    <div class="main-container">
        <?php include 'loading.php'; ?>
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="top-shelf">
                <div class="header-container">
                    <h2 class="header">Important Addresses</h2>
                </div>
                <div class="item-container">
                    <?php
                        foreach ($importantIp as $ip):
                    ?>
                    <div class="shelf-item content-container"><span><i class="fa fa-signal status-grey"></i><?php echo "$ip" ?></span></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="bottom-shelf">
                <div class="header-container">
                    <h2 class="header">Other Addresses</h2>
                </div>
                <div class="search-row">
                    <input type="text" class="search-bar" id="search-bar" placeholder="Search..">
                    <button class="search-btn"><i class="fa fa-search"></i></button>
                </div>
                <div class="scrollable-area" id="scrollable-area">
                    <?php foreach ($otherAddresses as $ip): ?>
                        <div class="shelf-item content-container" data-ip="<?php echo htmlspecialchars($ip); ?>">
                            <span><i class="fa fa-signal status-grey"></i><?php echo htmlspecialchars($ip); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/loading.js"></script>
    <script src="js/ipAddressStatusChecker.js"></script>
</body>
</html>