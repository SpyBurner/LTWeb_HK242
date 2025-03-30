<?php
namespace controller;

use core\Controller;

class HomeController extends Controller {
    public function index() {
        $this->requireAuth();

//        require_once __DIR__ . "/../view/home/home.php";
        echo 'hi';
    }
}