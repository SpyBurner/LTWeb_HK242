<?php
$env_file = __DIR__ . '/../.env';

if (!file_exists($env_file)) {
    throw new Exception('The .env file does not exist.');
}

$env = parse_ini_file($env_file);

// Define constants
define('DB_HOST', $env['DB_HOST']);
define('DB_NAME', $env['DB_NAME']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);
define('DB_CHARSET', $env['DB_CHARSET']);
