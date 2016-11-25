<?php
    date_default_timezone_set('Europe/Kiev');
    ini_set('display_errors', 1);
    if (explode('/', $_SERVER['REQUEST_URI'])[1] === "administrator") {
        require_once 'admin/bootstrap.php';
    }else{
        require_once 'web/bootstrap.php';
    }
?>