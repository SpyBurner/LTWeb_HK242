<?php

// Define routes and their corresponding files
global $route;
$routes = [
    '' => __DIR__ . '/view/home/home.php',
    'about' => __DIR__ . '/view/about/about.php',
    'contact' => __DIR__ . '/view/contact/contact.php',
    'login' => __DIR__ . '/view/account/login.php',
    'register' => __DIR__ . '/view/account/register.php',

    '404' => __DIR__ . '/view/404.php'
];

// Check if the route exists in the routes array
if (array_key_exists($route, $routes)) {
    require_once($routes[$route]);
} else {
    // If the route does not exist, show a 404 page
    require_once(__DIR__ . '/view/404.php');
}

?>