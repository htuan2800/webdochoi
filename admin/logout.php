<?php
    require('inc/essentials.php');
    session_start();
    // session_destroy();
    unset($_SESSION['mUser']);
    redirect('index.php');
?>