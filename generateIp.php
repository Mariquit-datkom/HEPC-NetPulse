<?php
    $ipAddressTextFile = "assets/docs/addresses/ipAddresses.txt";
    $otherAddressTextFile = "assets/docs/addresses/computers.txt";

    $servers = [];
    $switches = [];
    $biometrics = [];
    $importantDesktops = [];
    $otherDesktops = [];
    $laptops = [];
    $computeSticks = [];

    $allAddresses = [];

    if (file_exists($ipAddressTextFile)) {
        $lines = file($ipAddressTextFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentSection = '';

        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, '-- Servers --') !== false) {                 
                $currentSection = 'servers'; 
                continue; 
            } elseif (strpos($line, '-- Switch --') !== false) {
                $currentSection = 'switch';
                continue;
            } elseif (strpos($line, '-- Biometrics --') !== false) {
                $currentSection = 'biometrics';
                continue;
            }

            $parts = explode(' - ', $line, 2);
            $ip = trim(strip_tags($parts[0])); 
            $name = isset($parts[1]) ? trim(strip_tags($parts[1])) : null;
            
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $ip)) {

                if ($currentPage === 'dashboard.php') {
                    if ($currentSection === 'servers' && count($servers) < 5) $servers[] = $ip;
                    elseif ($currentSection === 'switch' && count($switches) < 5) $switches[] = $ip;
                    elseif ($currentSection === 'biometrics' && count($biometrics) < 5) $biometrics[] = $ip;
                } else {
                    if ($currentSection === 'servers') $servers[] = $ip;
                    elseif ($currentSection === 'switch') $switches[] = $ip;
                    elseif ($currentSection === 'biometrics') $biometrics[] = $ip;
                }

                $allAddresses[] = $ip;
            }
            
            if ($currentPage === 'dashboard.php') if(count($servers) >= 5 && count($switches) >= 5 && count($biometrics) >= 5) $currentSection = 'default';
        }
    }

    if (file_exists($otherAddressTextFile)) {        
        $lines = file($otherAddressTextFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentSection = '';

        foreach ($lines as $line) {
            $line = trim(($line));

            if (strpos($line, '-- Important Desktops --') !== false) {
                $currentSection = 'important';
                continue;
            } elseif (strpos($line, '-- Other Desktops --') !== false) {
                $currentSection = 'other';
                continue;
            } elseif (strpos($line, '-- Laptops --') !== false) {
                $currentSection = 'laptops';
                continue;
            } elseif (strpos($line, '-- Compute Sticks --') !== false) {
                $currentSection = 'computeSticks';
                continue;
            }

            $parts = explode(' - ', $line, 2);
            $ip = trim(strip_tags($parts[0])); 
            $name = isset($parts[1]) ? trim(strip_tags($parts[1])) : null;

            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $ip)) {

                    if ($currentSection === 'important') $importantDesktops[] = $ip;
                    elseif ($currentSection === 'other') $otherDesktops[] = $ip;
                    elseif ($currentSection === 'laptops') $laptops[] = $ip;
                    elseif ($currentSection === 'computeSticks') $computeSticks[] = $ip;
                
                $allAddresses[] = $ip;
            }
        }
    }

?> 