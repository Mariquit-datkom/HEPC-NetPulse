<?php
session_start();
session_write_close();
header('Content-Type: application/json');

date_default_timezone_set('Asia/Manila');

if (isset($_GET['ip'])) {
    $ip_address = $_GET['ip'];
    $ip = filter_var($_GET['ip'], FILTER_VALIDATE_IP) ?: escapeshellarg($_GET['ip']);

    $latency = '--'; 
    $status = 1;
    $output = [];
    
    exec("ping -n 1 -w 300 " . $ip, $output, $status);

    $response = [
        'color' => 'grey',
        'ms' => '--'
    ];
    
    if ($status === 0) {
        foreach ($output as $line) {
            if (preg_match('/time[=<](\d+)ms/', $line, $matches)) {
                $latency = (int)$matches[1];
                break;
            }
        }

        $_SESSION['pings'][$ip_address] = $latency;

        $response['ms'] = $latency;
        if ($latency === '--') $response['color'] = "grey";
        elseif ($latency < 20) $response['color'] = "green";
        elseif ($latency < 80) $response['color'] = "yellow";
        else $response['color'] = "red";
    }

    $logDate = date('Y-m-d');
    $logDir = "assets/docs/logs/$logDate/";

    if (!is_dir($logDir)) { mkdir($logDir, 0777, true); }

    $fileName = $logDir . $ip_address . ".txt";
    $timestamp = date('H:i:s');
    $msLogText = ($status !== 0) ? "Request timed out." : "time={$latency}ms";

    $logEntry = "[$timestamp] Reply: $msLogText" . PHP_EOL;
    file_put_contents($fileName, $logEntry, FILE_APPEND | LOCK_EX);

    echo json_encode($response);
    exit;
}