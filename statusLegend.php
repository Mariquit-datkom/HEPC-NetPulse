<?php
$legendIcon = 'fa fa-signal';

if (isset($currentPage)) {
    switch ($currentPage) {
        case 'servers.php':
            $legendIcon = 'far fa-server';
            break;
        case 'switches.php':
            $legendIcon = 'fas fa-sliders';
            break;
        case 'accessPoints.php':
            $legendIcon = 'far fa-circle-nodes';
            break;
        case 'biometrics.php':
            $legendIcon = 'fa fa-fingerprint';
            break;
        case 'categoryView.php':
            $legendIcon = 'far fa-wireless';
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
        <span><i class="<?php echo $legendIcon; ?> status-red"></i> = Poor Signal (300ms or greater)</span>
    </div>
    <div class="bottom-shelf-item">
        <span><i class="<?php echo $legendIcon; ?> status-grey"></i> = Timed Out / Error</span>
    </div>
</div>