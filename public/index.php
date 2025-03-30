<?php

// Enable error reporting in development (remove in production)
global $router;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/config.php';

require_once __DIR__ . '/../app/router.php';

session_start();

$router->dispatch();
?>