<?php

use core\Router;

require_once __DIR__ . '/core/Router.php';

$router = new Router();

// Linh
$router->addRoute('', 'HomeController', 'index');
$router->addRoute('home', 'HomeController', 'index');
$router->addRoute('about', 'AboutController', 'index');

//Chung
$router->addRoute('admin', 'AdminController', 'index');

//Tài
$router->addRoute('qna', 'QnaController', 'index');

$router->addRoute('profile', 'ProfileController', 'index');

$router->addRoute('account/login', 'AuthController', 'login');
$router->addRoute('account/register', 'AuthController', 'register');


//TEST
$router->addRoute('account/test_token', 'AuthController', 'test_token');

