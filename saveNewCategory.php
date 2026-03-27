<?php
session_start();
if (!isset($_SESSION['username'])) exit("Unauthorized");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (isset($input['name'])) {
    $newName = trim($input['name']);
    $newNameFormatted = "-- " . ucwords($newName) . " --";

    $filesToCheck = [
        "assets/docs/addresses/ipAddresses.txt",
        "assets/docs/addresses/computers.txt",
        "assets/docs/addresses/otherCategories.txt"
    ];
    
    $isDuplicate = false;

    foreach ($filesToCheck as $filePath) {
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);

            if (stripos($content, $newNameFormatted) !== false) {
                $isDuplicate = true;
                break;
            }
        }
    }

    if ($isDuplicate) {
        echo "duplicate";
        exit();
    }
    
    $file = "assets/docs/addresses/otherCategories.txt";
    $devices = $input['devices'] ?? [];
    $prefix = "";

    if (file_exists($file) && filesize($file) > 0) {
        $content = file_get_contents($file);

        if (strlen(trim($content)) > 0) {
            file_put_contents($file, rtrim($content)); 
            $prefix = "\n\n";
        }
    }

    $output = $prefix . $newNameFormatted  . "\n";
    
    foreach ($devices as $device) {
        $ip = $device['ip'] ?: '0.0.0.0'; // Fallback if empty
        $name = $device['name'] ?: '';

        if($name === '') $output .= "{$ip}\n";
        else $output .= "{$ip} - {$name}\n";
    }

    if (file_put_contents($file, $output, FILE_APPEND)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>