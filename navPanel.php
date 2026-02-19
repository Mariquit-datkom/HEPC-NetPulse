<div class="nav-panel content-container">
    <div class="nav-panel-header">
        <img src="assets/company_logo.png" alt="company_logo" class="company-logo">
    </div>
    <?php
        $navItems = [
            'dashboard.php' => ['icon' => 'fa-home', 'text' => 'Dashboard'],
            'desktops.php' => ['icon' => 'fa-desktop', 'text' => 'Desktops'],
            'laptops.php' => ['icon' => 'fa-laptop', 'text' => 'Laptops'],
            'ipAddresses.php' => ['icon' => 'fa-network-wired', 'text' => 'IP Addresses']
        ];

        foreach ($navItems as $page => $details):
            $isActive = ($currentPage === $page);
            $href = $isActive ? 'javascript:void(0)' : $page;
            $activeClass = $isActive ? 'active' : '';

        ?>
        <div class="nav-panel-item">
            <a href="<?php echo $href ?>" class="<?php echo $activeClass ?>">
                <i class="fa <?php echo $details['icon'] ?>"></i> <?php echo $details['text'] ?></a>
        </div>
    <?php endforeach; ?>
    <div class="filler-div"></div>
    <div class="sign-out-container nav-panel-item">
        <a href="logOut.php"><i class="fa fa-sign-out-alt"></i> Sign Out</a>
    </div>
</div>