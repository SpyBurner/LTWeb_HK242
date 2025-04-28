<?php
namespace controller;

use core\Controller;

class AboutController extends Controller {
    public function index() {
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT ABOUTCONTROLLER";
        }
        else{
            require_once __DIR__ . "/../view/about/about.php";
        }
    }
}