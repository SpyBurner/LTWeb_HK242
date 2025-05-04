<?php
namespace controller;

use core\Controller;

class QnaController extends Controller {
    public function index() {
        /* Needs:
         * qna list, paginated
         * faq list
         * */
        $qnaPage = $this->get('qnaPage');
        if ($qnaPage == null) {
            $qnaPage = 1;
        }



        $this->render('qna/qna');
    }

//    qna/add_question
    public function addQuestion(){
        echo "TODO";
    }
}