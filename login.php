<?php
require_once 'lib/TwistOAuth.phar';
require_once 'config.php';
require_once 'token.php';

session_start();

function redirect_to_main_page() {
    $url = WEBSITE_URL.'main.php';
    header("Location: $url");
    header('Content-Type: text/plain; charset=utf-8');
    exit("Redirecting to $url ...");
}

if (isset($_SESSION['logined'])) {
    redirect_to_main_page();
}

try {
    if (!isset($_SESSION['to'])) {
        $_SESSION['to'] = new TwistOAuth($ck,$cs);
        $_SESSION['to'] = $_SESSION['to']->renewWithRequestToken(WEBSITE_URL.'login.php');
        header("Location: {$_SESSION['to']->getAuthenticateUrl()}");
        header('Content-Type: text/plain; charset=utf-8');
        exit("Redirecting to {$_SESSION['to']->getAuthenticateUrl()} ...");
    } else {
        $_SESSION['to'] = $_SESSION['to']->renewWithAccessToken(filter_input(INPUT_GET, 'oauth_verifier'));
        $_SESSION['logined'] = true;
        session_regenerate_id(true);
        redirect_to_main_page();
    }
} catch (TwistException $e) {
    $_SESSION = array();
    header('Content-Type: text/plain; charset=utf-8', true, $e->getCode() ?: 500);
    exit($e->getMessage());
}
