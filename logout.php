<?php
    // Ends session and deletes temp data on log out
    session_start();
    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
?>