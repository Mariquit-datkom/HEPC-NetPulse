<?php
require_once 'x-head.php'; 
require_once 'userHeartbeatChecker.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: logIn.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$formMsg = ($_SESSION['formMsg']) ?? '';
unset($_SESSION['formMsg']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $currentPassword = $_POST['current-password'];
    $newPassword = $_POST['new-password'];
    $newPasswordConfirmation = $_POST['confirm-new-password'];

    if (empty($username) || empty($currentPassword)) {
        $_SESSION['formMsg'] = "<p style = 'color: red;'> Please fill all fields with correct information. </p>";
        header("Location: " . $currentPage);
        exit();
    } 
    
    if (!empty($newPassword) && $newPassword !== $newPasswordConfirmation) {
        $_SESSION['formMsg'] = "<p style = 'color: red;'> New password confirmation failed. Ensure new password and confirmation are the same. </p>";
        header("Location: " . $currentPage);
        exit();
    }
        
    try {            
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $_SESSION['username']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $_SESSION['formMsg'] = "<p style = 'color: red;'> Can't change credentials, incorrect current password. </p>";
            header("Location: " . $currentPage);
            exit();
        } 

        if (!empty($newPassword)) {
            $sql = "UPDATE users SET username = :username, password = :password WHERE user_id = :user_id";
            $params = [
                'username' => $username,
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                'user_id' => $user['user_id']
            ];
        } else {
            $sql = "UPDATE users SET username = :username WHERE user_id = :user_id";
            $params = [
                'username' => $username,
                'user_id' => $user['user_id']
            ];            
        }

        $pdo->prepare($sql)->execute($params);

        $_SESSION['formMsg'] = "<p style = 'color: green;'> Account credentials edited successfully. </p>";
        $_SESSION['username'] = $username;
        header("Location: " . $currentPage);
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "<p style='color: red; font-size: 14px; font-family: Arial; margin-top: 5px;'>A database error occurred. Please try again later.</p>";
        header("Location: logIn.php");
        exit();
    }    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Account Credentials</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navPanel.css">
    <link rel="stylesheet" href="css/editCredentials.css">
    <link rel="stylesheet" href="css/mobile.css" media="screen and (max-width: 800px)">
</head>
<body>
    <div class="main-container">
        <?php include 'navPanel.php'; ?>
        <div class="right-side-container">
            <div class="credential-editor-container">
                <div class="header-container">
                    <h2 class="header">Account Credentials</h2>
                </div>
                <div class="main-editor">
                    <div class="sub-container content-container">
                        <form method="POST" class="credentials-form" autocomplete="off">
                            <div class="form-row">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" name="username" id="username" class="form-input" value="<?php echo $_SESSION['username'] ?>">
                            </div>
                            <div class="form-row">
                                <label for="current-password" class="form-label">Current Password:</label>
                                <input type="text" name="current-password" id="current-password" class="form-input">
                            </div>
                            <div class="form-row">
                                <label for="new-password" class="form-label">New Password:</label>
                                <input type="text" name="new-password" id="new-password" class="form-input">
                            </div>
                            <div class="form-row">
                                <label for="confirm-new-password" class="form-label">Confirm New Password:</label>
                                <input type="text" name="confirm-new-password" id="confirm-new-password" class="form-input">
                            </div>
                            <div class="bottom-row">
                                <div class="form-msg-container">
                                    <span class="form-msg"><?php echo $formMsg ?></span>
                                </div>
                                <div class="button-row">
                                    <button type="submit" class="form-btn submit-btn" id="submit-btn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/userHeartbeat.js"></script>
    <script src="js/liveClock.js"></script>
    <script src="js/statusChecker.js"></script>
</body>
</html>