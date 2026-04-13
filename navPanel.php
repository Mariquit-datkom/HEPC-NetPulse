<div class="nav-panel content-container">
    <div class="nav-panel-header">
        <h2 class="title"><i class="fa fa-tower-broadcast"></i> NET PULSE</h2>
    </div>
    <div class="live-clock-container">
        <div id="current-date"></div>
        <div id="current-time"></div>
    </div>
    <div class="filler-div"></div>
    <div class="nav-item-main">
    <?php
        $navItems = [
            'dashboard.php' => ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'badge' => ''],
            'servers.php' => ['icon' => 'far fa-server', 'text' => 'Servers', 'badge' => 'servers'],
            'switches.php' => ['icon' => 'fas fa-sliders', 'text' => 'Switches', 'badge' => 'switches'],
            'accessPoints.php' => ['icon' => 'far fa-circle-nodes', 'text' => 'Access Points', 'badge' => 'access-points'],
            'biometrics.php' => ['icon' => 'fas fa-fingerprint', 'text' => 'Biometrics', 'badge' => 'biometrics'],
            'otherCategories.php' => ['icon' => 'far fa-folder', 'text' => 'Others', 'badge' => 'other-categories']
        ];

        foreach ($navItems as $page => $details):
            $isActive = ($currentPage === $page);
            $href = $isActive ? 'javascript:void(0)' : $page;
            $activeClass = $isActive ? 'active' : '';

        ?>
        <div class="nav-panel-item">
            <a href="<?php echo $href ?>" class="<?php echo $activeClass ?>">
                <i class="<?php echo $details['icon'] ?>"></i> <?php echo $details['text'] ?>
                <span id="badge-<?php echo $details['badge'] ?>" class="status-badge hide">0</span>
            </a>
        </div>
    <?php endforeach; ?>
    </div>
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
            <a class="logout" href="logOut.php">
                <i class="fa fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </div>
</div>