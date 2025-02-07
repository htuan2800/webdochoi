<?php
    require('admin/inc/essentials.php');
    session_start();
    // session_destroy();
    unset($_SESSION['user']);
    redirect('index.php');
?>