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
    $ipFromOtherCategories = [];

    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/--\s*(.*)\s*--/', $line, $matches)) {
                $text = trim($matches[1]);
                $formattedText = ucwords($text);
                $categories[] = $formattedText;
                continue;
            }

            $parts = explode(' - ', $line, 2);
            $ip = trim($parts[0]);
            $name = isset($parts[1]) ? trim($parts[1]) : null;

            // Check if line contains a valid IP address pattern
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $ip)) {
                $ipFromOtherCategories[] = $ip;
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
                        <input type="text" class="search-bar" id="search-bar" placeholder="Search.." autocomplete="off" autocomplete="off">
                        <button class="search-btn"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="scrollable-area">
                    <div class="no-results"> No results found. </div>
                    <?php if (!empty($categories)) foreach ($categories as $category): ?>
                        <div class="shelf-item content-container" 
                            onclick="window.location.href='categoryView.php?name=<?php echo urlencode($category); ?>'" 
                            style="cursor: pointer;">
                            <span class="display-item">
                                <div class="name-row">
                                    <i class="far fa-folder"></i>
                                    <span class="name-text"><strong><?php echo htmlspecialchars($category); ?></strong></span>
                                </div>
                            </span>
                            <span class="status-badge folder-badge hide">0</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>  
        </div>
    </div>
    <div id="categoryModal" class="modal">
        <div class="modal-content content-container">
            <div class="modal-header">
                <h2>Add New Category</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <label>Category Name:</label>
                    <input type="text" id="newCategoryName" placeholder="e.g. Production" autocomplete="off">
                </div>
                
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Device Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><input type="text" placeholder="0.0.0.0"></td><td><input type="text" placeholder="New Device"></td></tr>
                        <tr><td><input type="text" placeholder="0.0.0.0"></td><td><input type="text" placeholder="New Device"></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button id="saveCategoryBtn" class="add-category-btn">Save Category</button>
                <button type="button" id="addRowBtn" class="add-category-btn">+ Add Row</button>
            </div>
        </div>
    </div>
    <?php include 'systemAlert.php'; ?>
    
    <script> const currentPage = <?php echo json_encode($currentPage); ?>; </script>
    <script> const allAddresses = <?php echo json_encode($allAddresses); ?>; </script>
    <script> const ipFromOtherCategories = <?php echo json_encode($ipFromOtherCategories); ?>; </script>
    <script src="js/statusChecker.js"></script>
    <script src="js/addNewCategory.js"></script>
    <?php include 'scripts.php'; ?>
</body>
</html>