<?php
namespace controller;

use core\Controller;

class QnaController extends Controller {
    public function index() {
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT QNACONTROLLER";
        }
        else{
            require_once __DIR__ . "/../view/qna/qna.php";
        }
    }
}