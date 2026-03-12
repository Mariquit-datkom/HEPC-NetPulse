<?php
require_once 'dbConfig.php';
require_once 'x-head.php';
require_once 'userHeartbeatChecker.php'; 
if (session_status() === PHP_SESSION_NONE) session_start();
 

if (!isset($_SESSION['username'])) {
    header("Location: logIn.php");
    exit();
}

$logDir = 'assets/docs/logs/';
$folders = is_dir($logDir) ? array_diff(scandir($logDir), array('..', '.')) : [];
rsort($folders);

$availableWeeks = [];
foreach ($folders as $date) {
    $monday = date('Y-m-d', strtotime('monday this week', strtotime($date)));
    $sunday = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
    $weekKey = $monday . '_to_' . $sunday;

    if (!isset($availableWeeks[$weekKey])) {
        $availableWeeks[$weekKey] = [
            'start' => $monday,
            'end' => $sunday
        ];
    }
}

$sysLogsDir = 'assets/docs/system-event-logs/';
$systemLogs = is_dir($logDir) ? array_diff(scandir($sysLogsDir, SCANDIR_SORT_DESCENDING), array('..', '.')) : [];

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log Manager | HEPC-NetPulse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/logManager.css">
    <link rel="stylesheet" href="css/mobile.css" media="screen and (max-width: 800px)">
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>
        <div class="right-side-container">
            <div class="log-container">
                <h1>Network Log Archives</h1>
                
                <div class="tabs">
                    <button class="tab-btn active" onclick="showTab('daily')">Daily Logs</button>
                    <button class="tab-btn" onclick="showTab('weekly')">Weekly Archives</button>
                    <button class="tab-btn" onclick="showTab('system')">System Events</button>
                </div>

                <div id="daily">
                    <h3>Available Dates</h3>
                    <?php foreach ($folders as $date): ?>
                        <div class="log-card">
                            <span><i class="fa-regular fa-calendar-days"></i> <?php echo $date; ?></span>
                            <a href="downloadHandler.php?date=<?php echo $date; ?>&type=day" class="btn-download">
                                <i class="fa-solid fa-download"></i> Download Folder
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="weekly" style="display:none;">
                    <h3>Weeks with Records</h3>
                    <?php if (empty($availableWeeks)): ?>
                        <p style="color: #6b7280;">No weekly data available.</p>
                    <?php endif; ?>
                    <?php foreach ($availableWeeks as $week): ?>
                        <div class='log-card'>
                            <span>Week: <?php echo $week['start']; ?> to <?php echo $week['end']; ?></span>
                            <a href='downloadHandler.php?start=<?php echo $week['start']; ?>&end=<?php echo $week['end']; ?>&type=week' class='btn-download'>
                                <i class='fa-solid fa-file-zipper'></i> Download Week
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="system" style="display:none;">
                    <h3>System Event Logs</h3>
                    <?php if (empty($systemLogs)): ?>
                        <p style="color: #6b7280; padding: 20px;">No system events recorded yet.</p>
                    <?php else: ?>
                        <?php foreach ($systemLogs as $file): ?>
                            <div class="log-card">
                                <span><i class="fa-solid fa-file-lines"></i> <?php echo $file; ?></span>
                                <a href="assets/docs/system-event-logs/<?php echo $file; ?>" download="<?php echo $file; ?>" class="btn-download">
                                    <i class="fa-solid fa-download"></i> Download TXT
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/showTab.js"></script>    
    <script src="js/userHeartbeat.js"></script>
    <script src="js/liveClock.js"></script>
</body>
</html>