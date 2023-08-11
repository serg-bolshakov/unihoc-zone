<?php
    session_start();
    $_SESSION['auth'] = null;
    $_SESSION['name'] = null;
    $_SESSION['user_status'] = null;
    header("Location: login.php")
?>
