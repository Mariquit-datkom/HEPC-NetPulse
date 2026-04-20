<?php
    require_once 'x-head.php'; 
    require_once 'userHeartbeatChecker.php'; 

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $currentPage = basename($_SERVER['PHP_SELF']);
    $user = $_SESSION['username'];
    $now = time();
    $lockTimeout = 35; // 5 seconds longer than your 30s heartbeat

    try {
        // A. Remove any stale locks from the system
        $pdo->prepare("DELETE FROM page_locks WHERE :now - locked_at > :timeout")
            ->execute(['now' => $now, 'timeout' => $lockTimeout]);

        // B. Check if SOMEONE ELSE has a fresh lock on this page
        $stmt = $pdo->prepare("SELECT username FROM page_locks WHERE page_name = :page AND username != :user");
        $stmt->execute(['page' => $currentPage, 'user' => $user]);
        $lock = $stmt->fetch();

        if ($lock) {
            // Redirect to dashboard with the name of the person editing
            header("Location: dashboard.php?error=locked&by=" . urlencode($lock['username']));
            exit();
        }

        // C. Claim or refresh the lock for yourself
        $pdo->prepare("REPLACE INTO page_locks (page_name, username, locked_at) 
                    VALUES (:page, :user, :now)")
            ->execute(['page' => $currentPage, 'user' => $user, 'now' => $now]);

    } catch (PDOException $e) {
        error_log("Locking Error: " . $e->getMessage());
    }

    $target = match ($_GET['file'] ?? '') {
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

            $pdo->prepare("DELETE FROM page_locks WHERE page_name = :page AND username = :user")
                ->execute(['page' => $currentPage, 'user' => $user]);
        } else {
            $message = "<p style='color: #ef4444;'>Error: Could not write to file. Check permissions.</p>";
        }
    }

    $content = file_exists($filepath) ? file_get_contents($filepath) : "";
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