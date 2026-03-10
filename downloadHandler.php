<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) exit("Unauthorized");

$logDir = 'assets/docs/logs/';

if (isset($_GET['type']) && $_GET['type'] === 'day' && isset($_GET['date'])) {
    $targetDir = $logDir . $_GET['date'];
    if (!is_dir($targetDir)) exit("Directory not found.");

    createZipAndDownload([$_GET['date']], "Logs_" . $_GET['date'] . ".zip", $logDir);
} 

elseif (isset($_GET['type']) && $_GET['type'] === 'week' && isset($_GET['start']) && isset($_GET['end'])) {
    $start = strtotime($_GET['start']);
    $end = strtotime($_GET['end']);
    $datesToZip = [];

    for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
        $dateStr = date('Y-m-d', $i);
        if (is_dir($logDir . $dateStr)) {
            $datesToZip[] = $dateStr;
        }
    }

    if (empty($datesToZip)) exit("No data found for this week.");
    createZipAndDownload($datesToZip, "Weekly_Logs_" . $_GET['start'] . ".zip", $logDir);
}

function createZipAndDownload($folderNames, $zipName, $basePath) {
    $zip = new ZipArchive();
    $tmpFile = tempnam(sys_get_temp_dir(), 'zip');

    if ($zip->open($tmpFile, ZipArchive::CREATE) !== TRUE) exit("Could not create ZIP.");

    $absoluteBase = realpath($basePath) . DIRECTORY_SEPARATOR;

    foreach ($folderNames as $folder) {
        $folderPath = $absoluteBase . $folder;
        
        if (!is_dir($folderPath)) continue;

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folderPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                
                $relativePath = substr($filePath, strlen($absoluteBase));
                
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
    
    $zip->close();

    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipName . '"');
    header('Content-Length: ' . filesize($tmpFile));
    readfile($tmpFile);
    unlink($tmpFile); 
    exit;
}