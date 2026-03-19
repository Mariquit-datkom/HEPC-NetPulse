<?php
    require_once 'dbConfig.php';
    require_once 'x-head.php';
    session_start();

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    $confirmationMessage = "";
    if(isset($_SESSION['error'])) {
        $confirmationMessage = $_SESSION['error'];
        unset($_SESSION['error']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/logIn.css">
    <title>Log In</title>
</head>
<body>
    <div class="log-in-container">
        <div class="main-log-in">
            <img src="assets/company_logo.png" alt="company-logo" class="company-logo">
            <div class="title-container">
                <h2 class="title">IT NET PULSE</h2>
                <?php echo $confirmationMessage ?>
            </div>
            <form action="logInAuth.php" method="post" autocomplete="off">
                <div class="form-group">
                    <label for="username" class="form-label"><i class="fa fa-user"></i></label>
                    <input type="text" name="username" id="username" class="form-input" placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label"><i class="fa fa-key"></i></label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Password">
                </div>
                <div class="btn-container">
                    <input type="submit" value="Log In" class="btn">
                </div>
            </form>
        </div>
    </div>

    <div class="user-manual-container" onclick="openManual()">
        <i class="fa fa-book"></i> <span>USER MANUAL</span>
    </div>

    <script src="js/formCleaner.js"></script>
    <script>
        function openManual() {
            const userManualPath = 'assets/docs/user-manual/user-manual.pdf';
            window.open(userManualPath, '_blank');
        }
    </script>
</body>
</html>