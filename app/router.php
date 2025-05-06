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
$router->addRoute('qna/add_question', 'QnaController', 'addQuestion');
$router->addRoute('qna/qna_detail', 'QnaController', 'qnaDetail_API');
$router->addRoute('qna/add_message' , 'QnaController', 'addMessage');

$router->addRoute('profile', 'ProfileController', 'index');
$router->addRoute('profile/add_contact', 'ProfileController', 'addContact');
$router->addRoute('profile/edit_contact', 'ProfileController', 'editContact');
$router->addRoute('profile/delete_contact', 'ProfileController', 'deleteContact');
$router->addRoute('profile/update_avatar', 'ProfileController', 'updateAvatar_API');
$router->addRoute('profile/change_password', 'ProfileController', 'changePassword');

$router->addRoute('account/login', 'AuthController', 'login');
$router->addRoute('account/register', 'AuthController', 'register');
$router->addRoute('account/logout', 'AuthController', 'logout');


//TEST
$router->addRoute('account/test_token', 'AuthController', 'test_token');
$router->addRoute('profile/test_deleteUser', 'ProfileController', 'test_deleteUser');

