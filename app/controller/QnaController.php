<?php
namespace controller;

use core\Controller;

class QnaController extends Controller {
    public function index() {


        $this->render('qna/qna');
    }
}