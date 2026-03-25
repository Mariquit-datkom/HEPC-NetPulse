<?php
    require_once 'x-head.php'; 
    require_once 'userHeartbeatChecker.php';

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])) {
        header("Location: logIn.php");
        exit();
    }

    $currentPage = basename($_SERVER['PHP_SELF']);
    $allAddresses = $_SESSION['allAddresses'];

    $file = "assets/docs/addresses/otherCategories.txt";
    $categories = [];

    if (file_exists($file)) {
        $lines = file($file);

        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/--\s*(.*)\s*--/', $line, $matches)) {
                $text = trim($matches[1]);
                $formattedText = ucwords($text);
                $categories[] = $formattedText;
                continue;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/minor.css">
    <link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" href="css/otherCategories.css">
    <link rel="stylesheet" href="css/mobile.css" media="screen and (max-width: 800px)">
    <title>Other Categories</title>

    <script>
        if (sessionStorage.getItem('isFullscreen') === 'true') {
            document.documentElement.classList.add('fullscreen-active');
        }
    </script>
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>            
        <div class="right-side-container">
            <div class="top-shelf">
                <div class="header-container">
                    <h2 class="header">Other Categories</h2>
                </div>
                <div class="utility-row">
                    <div class="add-category-btn-container">
                        <button class="add-category-btn">+ Add Category</button>
                    </div>
                    <div class="search-row">
                        <input type="text" class="search-bar" id="search-bar" placeholder="Search.." autocomplete="off">
                        <button class="search-btn"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="scrollable-area">
                    <div class="no-results"> No results found. </div>
                    <?php
                        if (!empty($categories))    
                            foreach ($categories as $category):           
                    ?>
                    <div class="shelf-item content-container"?>
                        <span class="display-item">
                            <div class="name-row">
                                <i class="far fa-folder"></i>
                                <span class="name-text"><strong><?php echo htmlspecialchars($category); ?></strong></span>
                            </div>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>  
        </div>
    </div>
    <?php include 'systemAlert.php'; ?>
    
    <script> const currentPage = <?php echo json_encode($currentPage); ?>; </script>
    <script> const allAddresses = <?php echo json_encode($allAddresses); ?>; </script>
    <script src="js/statusChecker.js"></script>
    <?php include 'scripts.php'; ?>
</body>
</html>