<?php

use core\Router;

require_once __DIR__ . '/core/Router.php';

$router = new Router();

$router->addRoute('admin/contact-message', 'ContactMessageController', 'index');
$router->addRoute('admin', 'ContentManagerController', 'index');
$router->addRoute('admin/contact-message/fetch', 'ContactMessageController', 'getMessages');
$router->addRoute('admin/contact-message/view/:id', 'ContactMessageController', 'viewMessage');
$router->addRoute('admin/contact-message/delete/:id', 'ContactMessageController', 'deleteMessage');
$router->addRoute('admin/contact-message/mark-as-read/:id', 'ContactMessageController', 'markAsRead');
$router->addRoute('admin/contact-message/mark-as-replied/:id', 'ContactMessageController', 'markAsReplied');
$router->addRoute('admin/content-manager', 'ContentManagerController', 'index');
$router->addRoute('admin/content-manager/add', 'ContentManagerController', 'add');
$router->addRoute('admin/content-manager/edit', 'ContentManagerController', 'edit');
$router->addRoute('admin/content-manager/delete', 'ContentManagerController', 'delete');

$router->addRoute('admin/products', 'AdminProductsController', 'index');
$router->addRoute('admin/products/create', 'AdminProductsController', 'create');
$router->addRoute('admin/products/edit/:id', 'AdminProductsController', 'edit');
$router->addRoute('admin/products/delete/:id', 'AdminProductsController', 'delete');

$router->addRoute('admin/order', 'AdminOrderController', 'index');
$router->addRoute('admin/order/update-status', 'AdminOrderController', 'updateStatus');

$router->addRoute('admin/blog', 'AdminBlogController', 'getAllBlog');
$router->addRoute('admin/blog/comment', 'AdminBlogController', 'getCommentsByBlogid');
$router->addRoute('admin/blog/comment/delete', 'AdminBlogController', 'deleteComment');
$router->addRoute('admin/blog/edit', 'AdminBlogController', 'getPostInfo');
$router->addRoute('admin/blog/delete', 'AdminBlogController', 'deleteBlog');
$router->addRoute('admin/blog/create', 'AdminBlogController', 'createBlog');
$router->addRoute('admin/blog/search', 'AdminBlogController', 'searchBlog');
$router->addRoute('admin/blog/comment/search', 'AdminBlogController', 'searchComment');

$router->addRoute('admin/faq', 'AdminFAQController', 'index');
$router->addRoute('admin/faq/add_faq', 'AdminFAQController', 'addFaq');
$router->addRoute('admin/faq/edit_faq', 'AdminFAQController', 'editFaq');
$router->addRoute('admin/faq/delete_faq', 'AdminFAQController', 'deleteFaq');

$router->addRoute('admin/users', 'AdminUserController', 'index');
$router->addRoute('admin/users/add_user', 'AdminUserController', 'addUser');
$router->addRoute('admin/users/edit_user', 'AdminUserController', 'editUser');
$router->addRoute('admin/users/delete_user', 'AdminUserController', 'deleteUser');
$router->addRoute('admin/users/reset_password', 'AdminUserController', 'resetPassword');



// Linh
$router->addRoute('', 'UserHomeController', 'index');
$router->addRoute('contact-message', 'ContactMessageController', 'submit');
$router->addRoute('about', 'AboutController', 'index');
//Tài
$router->addRoute('qna', 'QnaController', 'index');
$router->addRoute('qna/add_question', 'QnaController', 'addQuestion');
$router->addRoute('qna/qna_detail', 'QnaController', 'qnaDetail_API');
$router->addRoute('qna/add_message' , 'QnaController', 'addMessage');

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
$router->addRoute('account/forget_password', 'AuthController', 'forgetPassword');
$router->addRoute('account/reset_password', 'AuthController', 'resetPassword');

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
