<?php
namespace controller;

use core\Controller;

class HomeController extends Controller {
    public function index() {
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT HOMECONTROLLER";
        }
        else{
            require_once __DIR__ . "/../view/home/home.php";
        }
    }
}