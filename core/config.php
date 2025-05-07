<?php
namespace config;
$env_file = __DIR__ . '/../.env';

use Exception;

if (!file_exists($env_file)) {
//    throw new Exception('The .env file does not exist.');
    die('The .env file does not exist.');
}

$env = parse_ini_file($env_file);

// Define constants
define('DB_HOST', $env['DB_HOST']);
define('DB_PORT', $env['DB_PORT']);
define('DB_NAME', $env['DB_NAME']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);
define('DB_CHARSET', $env['DB_CHARSET']);

$conf_file = __DIR__ . '/../config.ini';
$conf = parse_ini_file($conf_file);

define('MESSAGE_DURATION', $conf['MESSAGE_DURATION']);
define('SECRET_KEY', $conf['SECRET_KEY']);
define('USE_LOGGER', $conf['USE_LOGGER']);

//Pagination
define('PAGINATION_SIZE', $conf['PAGINATION_SIZE']);
define('PAGINATION_NUMBER', $conf['PAGINATION_NUMBER']);


//Token lifetime calculation
$lifetime_suffix = [
    's' => 1,
    'm' => 60,
    'h' => 3600,
    'd' => 86400,
    'w' => 604800,
    'M' => 2592000,
    'y' => 31536000
];

$token_lifetime_value = (int) substr($conf['TOKEN_LIFETIME'], 0, -1);
$token_lifetime_suffix = $lifetime_suffix[(substr($conf['TOKEN_LIFETIME'], -1))];

define('TOKEN_LIFETIME', $token_lifetime_value * $token_lifetime_suffix);

define('ENCRYPT_METHOD', $conf['ENCRYPT_METHOD']);

define('RESET_PASSWORD_TOKEN_LIFETIME', $conf['RESET_PASSWORD_TOKEN_LIFETIME']);

define('BASE_URL', 'http://localhost');
const MAZER_BASE_URL = '/assets/mazer-template/assets';

const IMAGE_UPLOAD_URL = __DIR__ . '/../public/assets/repo';

const IMAGE_UPLOAD_URL_PUBLIC = 'assets/repo';

const NO_IMAGE_URL_PUBLIC = 'assets/img/No_Image_Available.jpg';

const DEFAULT_AVATAR_URL = 'assets/img/avatar_male.png';

const DEFAULT_MOD_AVATAR_URL =  'assets/img/logo_logo.png';

// added by Linh
const STATIC_IMAGE_URL = '/assets/img/';

const ADMIN_CONFIG_URL = __DIR__ . '/admin-config.json';

const DEFAULT_IMAGE_NAME = 'No_Image_Available.jpg';

const DEFAULT_PRODUCT_IMAGE_URL = 'assets/img/cake_placeholder.jpg';
