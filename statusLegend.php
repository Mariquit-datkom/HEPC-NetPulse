<?php
$legendIcon = 'fa fa-signal';

if (isset($currentPage)) {
    switch ($currentPage) {
        case 'biometrics.php':
            $legendIcon = 'fa fa-fingerprint';
            break;
        case 'desktops.php':
            $legendIcon = 'fa fa-desktop';
            break;
        case 'laptops.php':
            $legendIcon = 'fa fa-laptop';
            break;
        case 'computeSticks.php':
            $legendIcon = 'fab fa-usb';
            break;
    }
}
?>

<div class="bottom-shelf">
    <div class="bottom-shelf-item">
        <span><i class="<?php echo $legendIcon; ?> status-green"></i> = Excellent Signal (1ms - 89ms)</span>
    </div>
    <div class="bottom-shelf-item">
        <span><i class="<?php echo $legendIcon; ?> status-yellow"></i> = Good Signal (90ms - 299ms)</span>
    </div>
    <div class="bottom-shelf-item">
        <span><i class="<?php echo $legendIcon; ?> status-red"></i> = Poor Signal (300ms pataas)</span>
    </div>
    <div class="bottom-shelf-item">
        <span><i class="<?php echo $legendIcon; ?> status-grey"></i> = Timed Out / Error</span>
    </div>
</div>