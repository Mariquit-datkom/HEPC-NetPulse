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
    <link rel="stylesheet" href="css/ipAddresses.css">
    <title>IP Addresses</title>
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="top-shelf">
                <div class="header-container">
                    <h2 class="header">Important Addresses</h2>
                </div>
                <div class="item-container">
                    <?php
                        $importantIp = array("192.168.1.1", "192.168.1.2", "192.168.1.3", "192.168.1.4");

                        foreach ($importantIp as $ip):
                    ?>
                    <div class="shelf-item content-container"><span><i class="fa fa-signal"></i><?php echo "$ip" ?></span></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="bottom-shelf">
                <div class="header-container">
                    <h2 class="header">Other Addresses</h2>
                </div>
            </div>
        </div>
    </div>
</body>
</html>