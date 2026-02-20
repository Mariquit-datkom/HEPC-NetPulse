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
    <link rel="stylesheet" href="css/laptops.css">
    <title>Desktops</title>
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="shelf">
                <div class="header-container">
                    <h2 class="header">Laptops</h2>
                </div>
                <div class="scrollable-area">
                    <?php
                        $importantIp = array("192.168.1.1", "192.168.1.2", "192.168.1.3", "192.168.1.4");

                        foreach ($importantIp as $index => $ip):
                            if ($index % 5 == 0) echo "<div class='shelf-row'>";
                    ?>
                    <div class="shelf-item content-container">
                        <i class="fa fa-laptop"></i>
                        <span class="desktop-name"><?php echo "Laptop " . $index + 1 ?></span>
                        <span class="ip"><?php echo "($ip)" ?></span>
                    </div>
                    <?php 
                        if (($index + 1) % 5 == 0 || ($index + 1) == count($importantIp)) echo "</div>";
                        endforeach; 
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>