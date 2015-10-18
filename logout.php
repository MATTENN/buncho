<?php
require_once 'config.php';

session_start();

if(isset($_SESSION)){
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();
    header("Location: ".WEBSITE_URL);
}else{
    header("Location: ".WEBSITE_URL."/main.php");
}
