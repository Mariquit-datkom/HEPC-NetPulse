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
    <link rel="stylesheet" href="css/desktops.css">
    <title>Desktops</title>
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="top-shelf">
                <div class="header-container">
                    <h2 class="header">Important Desktops</h2>
                </div>
                <div class="item-container">
                    <?php
                        $importantIp = array("192.168.1.1", "192.168.1.2", "192.168.1.3", "192.168.1.4");

                        $count = 0;
                        foreach ($importantIp as $ip):
                            ++$count;
                    ?>
                    <div class="shelf-item content-container">
                        <i class="fa fa-desktop"></i>
                        <span class="desktop-name"><?php echo "Desktop $count" ?></span>
                        <span class="ip"><?php echo "($ip)" ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="bottom-shelf">
                <div class="header-container">
                    <h2 class="header">Other Desktops</h2>
                </div>
                <div class="search-row">
                    <input type="text" class="search-bar" id="search-bar" placeholder="Search..">
                    <button class="search-btn"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>