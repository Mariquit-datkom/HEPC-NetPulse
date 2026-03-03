<div class="nav-panel content-container">
    <div class="nav-panel-header">
        <h2 class="title">IT NET PULSE</h2>
    </div>
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
    <div class="sign-out-container nav-panel-item">
        <a href="logOut.php"><i class="fa fa-sign-out-alt"></i> Sign Out</a>
    </div>
</div>