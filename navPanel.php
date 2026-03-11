<div class="nav-panel content-container">
    <div class="nav-panel-header">
        <h2 class="title"><i class="fa fa-tachometer-alt"></i> IT NET PULSE</h2>
    </div>
    <div class="live-clock-container">
        <div id="current-time"></div>
        <div id="current-date"></div>
    </div>
    <div class="filler-div"></div>
    <?php
        $navItems = [
            'dashboard.php' => ['icon' => 'fa fa-home', 'text' => 'Dashboard'],
            'ipAddresses.php' => ['icon' => 'fa fa-network-wired', 'text' => 'IP Addresses'],
            'biometrics.php' => ['icon' => 'fa fa-fingerprint', 'text' => 'Biometrics'],
            'desktops.php' => ['icon' => 'fa fa-desktop', 'text' => 'Desktops'],
            'laptops.php' => ['icon' => 'fa fa-laptop', 'text' => 'Laptops'],
            'computeSticks.php' => ['icon' => 'fab fa-usb', 'text' => 'Compute Sticks']
        ];

        foreach ($navItems as $page => $details):
            $isActive = ($currentPage === $page);
            $href = $isActive ? 'javascript:void(0)' : $page;
            $activeClass = $isActive ? 'active' : '';

        ?>
        <div class="nav-panel-item">
            <a href="<?php echo $href ?>" class="<?php echo $activeClass ?>">
                <i class="<?php echo $details['icon'] ?>"></i> <?php echo $details['text'] ?></a>
        </div>
    <?php endforeach; ?>
    <div class="filler-div"></div>
    <div class="nav-menu-container">
        <input type="checkbox" id="menu-toggle" class="menu-checkbox">
        
        <label for="menu-toggle" class="menu-button">
            <i class="fa fa-bars"></i>
        </label>

        <div class="menu-dropdown">
            <a href="logManager.php" class="menu-item">
                <i class="fa-solid fa-file-export"></i> Download Logs
            </a>
            <a href="editAddresses.php">
                <i class="fa fa-edit"></i> Edit Addresses
            </a>
            <a href="#" id="fullscreen-btn">
                <i class="fas fa-expand"></i> Fullscreen
            </a>
            <a class="logout" href="logOut.php">
                <i class="fa fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </div>
</div>