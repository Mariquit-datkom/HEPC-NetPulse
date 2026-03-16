<?php
session_start();
session_write_close();
header('Content-Type: application/json');

date_default_timezone_set('Asia/Manila');

function getIpGroup($targetIp) {
    $files = ['assets/docs/addresses/ipAddresses.txt', 'assets/docs/addresses/computers.txt'];
    
    foreach ($files as $file) {
        if (!file_exists($file)) continue;
        
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentGroup = "Unknown";

        foreach ($lines as $line) {
            $line = trim($line);
            // Check if line is a header like -- Servers --
            if (preg_match('/^--\s*(.*?)\s*--$/', $line, $matches)) {
                $currentGroup = trim($matches[1]);
                continue;
            }
            
            // Extract IP from line (handling your "IP - Name" format)
            $parts = explode('-', $line);
            $ip = trim($parts[0]);

            if ($ip === $targetIp) {
                return $currentGroup;
            }
        }
    }
    return "Unknown";
}

if (isset($_GET['ip'])) {
    $ip_address = $_GET['ip'];
    $ip = filter_var($_GET['ip'], FILTER_VALIDATE_IP) ?: escapeshellarg($_GET['ip']);

    $latency = '--'; 
    $status = 1;
    $output = [];
    $group = getIpGroup($ip);
    
    exec("ping -n 1 " . $ip, $output, $status);

    $response = [
        'color' => 'grey',
        'ms' => '--',
        'group' => $group
    ];
    
    if ($status === 0) {
        foreach ($output as $line) {
            if (preg_match('/time[=<](\d+)ms/', $line, $matches)) {
                $latency = (int)$matches[1];
                break;
            }
        }

        $_SESSION['pings'][$ip_address] = $latency;

        if ($latency === null || $status !== 0) {
            $response['ms'] = '--'; 
            $response['color'] = 'grey';
        } else {
            $response['ms'] = $latency;
            if ($latency === '--') $response['color'] = "grey";
            elseif ($latency < 90) $response['color'] = "green";
            elseif ($latency < 300) $response['color'] = "yellow";
            else $response['color'] = "red";
        }
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