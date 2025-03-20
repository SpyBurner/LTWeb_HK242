<?php

session_start();

// Get the URI and remove the leading slash
$route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Include the router
require_once(__DIR__ . '/../app/router.php');

?>