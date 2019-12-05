<?php
    require('model/clean.php');
    session_start();
    $_SESSION['sess_uid'] = NULL;
    header("Location: /$server_location/index.php");
?>