<?php
session_start();
if (!isset($_SESSION['username'])) exit("Unauthorized");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (isset($input['name'])) {
    $file = "assets/docs/addresses/otherCategories.txt";
    $newName = trim($input['name']);
    $devices = $input['devices'] ?? [];
    
    $prefix = "";

    if (file_exists($file) && filesize($file) > 0) {
        $content = file_get_contents($file);

        if (strlen(trim($content)) > 0) {
            file_put_contents($file, rtrim($content)); 
            $prefix = "\n\n";
        }
    }

    $output = $prefix . "-- " . ucwords($newName) . " --\n";
    
    foreach ($devices as $device) {
        $ip = $device['ip'] ?: '0.0.0.0'; // Fallback if empty
        $name = $device['name'] ?: 'Unknown Device';
        $output .= "{$ip} - {$name}\n";
    }

    if (file_put_contents($file, $output, FILE_APPEND)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>