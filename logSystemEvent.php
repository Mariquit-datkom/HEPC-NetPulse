<?php
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['event_type'])) {
    $type = $data['event_type'];
    $reason = $data['reason'] ?? 'N/A';
    
    date_default_timezone_set('Asia/Manila');
    $timestamp = date('H:i:s');
    $today = date('Y-m-d');

    $logDir = 'assets/docs/system-event-logs/';
    $filePath = $logDir . $today . '-system-events.txt';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    $logEntry = "[$timestamp] $type - $reason" . PHP_EOL;

    file_put_contents($filePath, $logEntry, FILE_APPEND | LOCK_EX);

    echo json_encode(['status' => 'success', 'file' => $today . '.txt']);
}
?>