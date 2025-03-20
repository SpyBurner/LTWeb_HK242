<?php

// Define routes and their corresponding files
$routes = [
    '' => __DIR__ . '/view/home/home.php',
    'about' => __DIR__ . '/view/about/about.php',
    // Add more routes here
];

// Check if the route exists in the routes array
if (array_key_exists($route, $routes)) {
    require_once($routes[$route]);
} else {
    // If the route does not exist, show a 404 page
    require_once(__DIR__ . '/view/404.php');
}

?>