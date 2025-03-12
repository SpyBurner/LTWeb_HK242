<?php

session_start();

$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

// echo $route;

if (empty($route)) {
    require_once(__DIR__.'/../app/view/home/home.php');
}
else {
    switch($route) {
        case 'contact':
            require_once(__DIR__.'/../app/view/about/contact.php');
            break;
        default:
            require_once(__DIR__.'/../app/view/404.php');
            break;
    }
}



?>