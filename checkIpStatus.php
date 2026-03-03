<?php
session_start();
header('Content-Type: application/json');

if (isset($_GET['ip'])) {
    $ip_address = $_GET['ip'];
    $ip = filter_var($_GET['ip'], FILTER_VALIDATE_IP) ?: escapeshellarg($_GET['ip']);
    
    exec("ping -n 1 -w 1000 " . $ip, $output, $status);

    $response = [
        'color' => 'grey',
        'ms' => '--'
    ];
    
    if ($status === 0) {
        $latency = 0;
        foreach ($output as $line) {
            if (preg_match('/time[=<](\d+)ms/', $line, $matches)) {
                $latency = (int)$matches[1];
                break;
            }
        }

        $_SESSION['pings'][$ip_address] = $latency;

        $response['ms'] = $latency;
        if ($latency < 20) $response['color'] = "green";
        elseif ($latency < 80) $response['color'] = "yellow";
        else $response['color'] = "red";
    }

    echo json_encode($response);
    exit;
}