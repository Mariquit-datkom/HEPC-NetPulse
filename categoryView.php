<?php
    require_once 'x-head.php'; 
    require_once 'userHeartbeatChecker.php';

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    // Get the category from the URL, e.g., categoryView.php?name=Production
    $categoryName = isset($_GET['name']) ? trim($_GET['name']) : '';
    $filename = "assets/docs/addresses/otherCategories.txt";
    $devices = [];

    if (file_exists($filename) && !empty($categoryName)) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $inSection = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Check for the specific header, e.g., "-- Production --"
            if (strcasecmp($line, "-- $categoryName --") === 0) {
                $inSection = true;
                continue;
            }

            // If we hit another header, stop reading
            if ($inSection && preg_match('/^--.*--$/', $line)) {
                break;
            }

            if ($inSection) {
                $parts = explode(' - ', $line, 2);
                if (count($parts) >= 1) {
                    $ip = trim($parts[0]);
                    $name = isset($parts[1]) ? trim($parts[1]) : '';
                    $devices[] = ['ip' => $ip, 'name' => $name];
                }
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
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/categoryView.css">
    <title><?php echo htmlspecialchars($categoryName); ?></title>
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="top-shelf">
                <div class="header-container">
                    <h2 class="header"><?php echo htmlspecialchars($categoryName); ?></h2>
                </div>
                <div class="search-row">
                    <input type="text" class="search-bar" id="search-bar" placeholder="Search.." autocomplete="off">
                    <button class="search-btn"><i class="fa fa-search"></i></button>
                </div>
                <div class="scrollable-area">
                    <div class="no-results"> No results found. </div>
                    <?php foreach ($devices as $item): ?>
                    <div class="shelf-item content-container" data-ip="<?php echo htmlspecialchars($item['ip']) ?>">
                        <span class="display-item">
                            <div class="name-row">
                                <i class="far fa-wireless status-grey"></i>
                                <span class="name-text"><strong><?php echo htmlspecialchars($item['name'] ?: $item['ip']); ?></strong></span>
                            </div>
                            <?php if ($item['name']): ?>
                                <small class="ip-text"><?php echo htmlspecialchars($item['ip']); ?></small>
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