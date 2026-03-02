<?php
if (isset($_GET['ip'])) {
    $ip = filter_var($_GET['ip'], FILTER_VALIDATE_IP) ?: escapeshellarg($_GET['ip']);
    
    exec("ping -n 1 -w 1000 " . $ip, $output, $status);

    if ($status !== 0) {
        echo "grey"; 
        exit;  // Unresponsive
    } 
    
    $latency = 0;
    foreach ($output as $line) {
        if (preg_match('/time[=<](\d+)ms/', $line, $matches)) {
            $latency = (int)$matches[1];
            break;
        }
    }

    if ($latency < 10) {
        echo "green";
    } elseif ($latency < 60) {
        echo "yellow";
    } else {
        echo "red";
    }
}