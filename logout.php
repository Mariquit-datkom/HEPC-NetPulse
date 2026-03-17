<?php
    // Ends session and deletes temp data on log out
    session_start();
    session_unset();
    session_destroy();

?>

<script>
    sessionStorage.removeItem('hasLoaded');
    sessionStorage.removeItem('isFullscreen');
    sessionStorage.removeItem('ipStatusRegistry');
    window.location.href = "logIn.php";
</script>