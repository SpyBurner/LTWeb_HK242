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
$router->addRoute('admin/blog', 'AdminController', 'getAllBlog');
$router->addRoute('admin/blog/comment', 'AdminController', 'getCommentsByBlogid');
$router->addRoute('admin/blog/comment/delete', 'AdminController', 'deleteComment');
$router->addRoute('admin/blog/edit', 'AdminController', 'getPostInfo');
$router->addRoute('admin/blog/delete', 'AdminController', 'deleteBlog');
$router->addRoute('admin/blog/create', 'AdminController', 'createBlog');
$router->addRoute('admin/blog/search', 'AdminController', 'searchBlog');
$router->addRoute('admin/blog/comment/search', 'AdminController', 'searchComment');

//Tài
$router->addRoute('qna', 'QnaController', 'index');

// Minh
$router->addRoute('news', 'BlogController', 'index');
$router->addRoute('news/search', 'BlogController', 'search');
$router->addRoute('blog/view', 'BlogController', 'getBlogInfo');
$router->addRoute('blog/like', 'BlogController', 'handleLike');

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

