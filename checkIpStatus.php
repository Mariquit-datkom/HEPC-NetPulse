<?php
session_start();
session_write_close();
header('Content-Type: application/json');

if (isset($_GET['ip'])) {
    $ip_address = $_GET['ip'];
    $ip = filter_var($_GET['ip'], FILTER_VALIDATE_IP) ?: escapeshellarg($_GET['ip']);

    $latency = '--'; 
    $status = 1;
    $output = [];
    
    exec("ping -n 1 -w 800 " . $ip, $output, $status);

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
    
    $isTimeout = ($latency === '--');
    $isSpike = (!$isTimeout && $latency > 150); 
    $msLogText = $isTimeout ? "Request timed out." : "time={$latency}ms";
    $logEntry = "[$timestamp] Reply: $msLogText";

    if ($isTimeout || $isSpike) {
        file_put_contents($fileName, $logEntry . " (PRIORITY)" . PHP_EOL, FILE_APPEND | LOCK_EX);
    } else {
        if (!isset($_SESSION['log_buffer'][$ip_address])) {
            $_SESSION['log_buffer'][$ip_address] = [];
        }
        
        $_SESSION['log_buffer'][$ip_address][] = $logEntry;

        if (count($_SESSION['log_buffer'][$ip_address]) >= 5) {
            $logData = implode(PHP_EOL, $_SESSION['log_buffer'][$ip_address]) . PHP_EOL;
            file_put_contents($fileName, $logData, FILE_APPEND | LOCK_EX);
            $_SESSION['log_buffer'][$ip_address] = [];
        }
    }

    echo json_encode($response);
    exit;
}