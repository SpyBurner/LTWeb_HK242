<?php

use core\Router;

require_once __DIR__ . '/core/Router.php';

$router = new Router();

// Linh
$router->addRoute('', 'UserHomeController', 'index');
$router->addRoute('contact-message', 'ContactMessageController', 'submit');
$router->addRoute('about', 'AboutController', 'index');

$router->addRoute('admin', 'AdminDashboardController', 'index');
$router->addRoute('admin/contact-message', 'ContactMessageController', 'index');
$router->addRoute('admin/contact-message/fetch', 'ContactMessageController', 'getMessages');
$router->addRoute('admin/contact-message/view/:id', 'ContactMessageController', 'viewMessage');
$router->addRoute('admin/contact-message/delete/:id', 'ContactMessageController', 'deleteMessage');
$router->addRoute('admin/contact-message/mark-as-read/:id', 'ContactMessageController', 'markAsRead');
$router->addRoute('admin/contact-message/mark-as-replied/:id', 'ContactMessageController', 'markAsReplied');
$router->addRoute('admin/content-manager', 'ContentManagerController', 'index');
$router->addRoute('admin/content-manager/add', 'ContentManagerController', 'add');
$router->addRoute('admin/content-manager/edit', 'ContentManagerController', 'edit');
$router->addRoute('admin/content-manager/delete', 'ContentManagerController', 'delete');

//Chung
$router->addRoute('admin/products', 'AdminProductsController', 'index');
$router->addRoute('admin/products/create', 'AdminProductsController', 'create');
$router->addRoute('admin/products/edit/:id', 'AdminProductsController', 'edit');
$router->addRoute('admin/products/delete/:id', 'AdminProductsController', 'delete');

$router->addRoute('admin/order', 'AdminOrderController', 'index');
$router->addRoute('admin/order/update-status', 'AdminOrderController', 'updateStatus');
//Tài
$router->addRoute('qna', 'QnaController', 'index');

$router->addRoute('profile', 'ProfileController', 'index');
$router->addRoute('profile/add_contact', 'ProfileController', 'addContact');
$router->addRoute('profile/edit_contact', 'ProfileController', 'editContact');
$router->addRoute('profile/delete_contact', 'ProfileController', 'deleteContact');
$router->addRoute('profile/update_avatar', 'ProfileController', 'updateAvatar_API');
$router->addRoute('profile/change_password', 'ProfileController', 'changePassword');

$router->addRoute('account/login', 'AuthController', 'login');
$router->addRoute('account/register', 'AuthController', 'register');
$router->addRoute('account/logout', 'AuthController', 'logout');

$router->addRoute('products', 'ProductsController', 'index');
$router->addRoute('products/detail/:id', 'ProductsController', 'detail');

//TEST
$router->addRoute('account/test_token', 'AuthController', 'test_token');
$router->addRoute('profile/test_deleteUser', 'ProfileController', 'test_deleteUser');

//cart
$router->addRoute('cart', 'CartController', 'index');
$router->addRoute('checkout', 'CartController', 'checkout');
$router->addRoute('checkout/paymentValid', 'CartController', 'paymentValid');
$router->addRoute('checkout/confirmation/:id', 'CartController', 'confirmation');
$router->addRoute('cart/add/:id', 'CartController', 'add');
$router->addRoute('cart/remove/:id', 'CartController', 'remove');
$router->addRoute('cart/reduce/:id', 'CartController', 'reduce');



$router->addRoute('orders', 'OrdersController', 'index');
$router->addRoute('orders/my-orders', 'OrdersController', 'myOrders');
$router->addRoute('orders/detail/:id', 'OrdersController', 'orderDetail');
