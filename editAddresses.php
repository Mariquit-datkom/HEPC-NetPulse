<?php
    require_once 'x-head.php'; 
    require_once 'userHeartbeatChecker.php'; 

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $target = match ($_GET['file'] ?? '') {
        'computers' => 'computers.txt',
        'otherCategories'    => 'otherCategories.txt',
        default     => 'ipAddresses.txt',
    };

    $filepath = "assets/docs/addresses/" . $target;
    $message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
        if (file_put_contents($filepath, $_POST['content'], LOCK_EX) !== false) {
            $message = "<p style='color: #22c55e;'>File updated successfully!</p>";
            echo '<script>';
            echo 'sessionStorage.removeItem("ipStatusRegistry")';
            echo '</script>';
        } else {
            $message = "<p style='color: #ef4444;'>Error: Could not write to file. Check permissions.</p>";
        }
    }

    $content = file_exists($filepath) ? file_get_contents($filepath) : "";
    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit IP Addresses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/editAddresses.css">
    <link rel="stylesheet" href="css/mobile.css" media="screen and (max-width: 800px)">
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>
        <div class="right-side-container">
            <div class="content-container editor-container">
                <h2>Network Address Editor</h2>
                <?php echo $message; ?>

                <div class="file-tabs">
                    <a href="?file=main" class="tab <?php echo $target === 'ipAddresses.txt' ? 'active-tab' : ''; ?>">IP Addresses</a>
                    <a href="?file=computers" class="tab <?php echo $target === 'computers.txt' ? 'active-tab' : ''; ?>">Computers/Laptops</a>
                    <a href="?file=otherCategories" class="tab <?php echo $target === 'otherCategories.txt' ? 'active-tab' : ''; ?>">Other Categories</a>
                </div>

                <form class="text-editor-form" method="POST">
                    <label>Editing: <?php echo $target; ?></label>
                    <textarea name="content" class="text-editor"><?php echo htmlspecialchars($content); ?></textarea>
                    <div style="margin-top: 15px;">
                        <button type="submit" class="save-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/userHeartbeat.js"></script>
    <script src="js/liveClock.js"></script>
    <script src="js/statusChecker.js"></script>
</body>
</html>