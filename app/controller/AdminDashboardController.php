<?php
namespace controller;


use core\Controller;
use core\SessionHelper;
use http\Header;
use service\ProductsService;
use service\CategoryService;
use service\OrderService;

class AdminDashboardController extends BaseController {
    public function index() {
        $this->render('admin/dashboard', [
            'title' => 'Dashboard',
        ]);
    }
}