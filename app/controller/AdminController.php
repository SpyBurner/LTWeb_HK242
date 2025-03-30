<?php
namespace controller;

use core\Controller;

class AdminController extends Controller {
    public function index() {
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT AdminController";
        }
        else{
            require_once __DIR__ . "/../view/admin/dashboard.php";
        }
    }

    public function contact(){
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT AdminController";
        }
        else{
            require_once __DIR__ . "/../view/admin/contact.php";
        }
    }

    public function qna(){
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT AdminController";
        }
        else{
            require_once __DIR__ . "/../view/admin/qna.php";
        }
    }
}