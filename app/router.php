<?php

use core\Router;

require_once __DIR__ . '/core/Router.php';

$router = new Router();

// Web routes
$router->addRoute('', 'HomeController', 'index');
$router->addRoute('home', 'HomeController', 'index');
$router->addRoute('about', 'HomeController', 'about');
$router->addRoute('contact', 'HomeController', 'contact');

$router->addRoute('account/test_token', 'AuthController', 'test_token');
$router->addRoute('account/login', 'AuthController', 'login');
$router->addRoute('account/register', 'AuthController', 'register');

