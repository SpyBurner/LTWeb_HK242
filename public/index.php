<?php

// Enable error reporting in development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/config.php';

session_start();
// Get the URI and remove the leading slash
$route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Include the router
require_once(__DIR__ . '/../app/router.php');
?>