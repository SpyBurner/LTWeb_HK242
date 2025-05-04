<?php
namespace controller;

use core\Controller;
use core\Logger;
use service\FaqService;
use service\QnaService;

class QnaController extends Controller {
    public function index() {
        /* Needs:
         * qna list, paginated
         * faq list
         * */
        $search = $this->get('search');

        if ($search == null){
            $qnaPage = $this->get('qnaPage');
            if ($qnaPage == null) {
                $qnaPage = 1;
            }
            $limit = PAGINATION_SIZE;

            // Get qna list
            $result = QnaService::findAll($limit, $qnaPage);
            if (!$result['success']){
                Logger::log("QnaController::index" . $result['message']);
                $this->redirectWithMessage('/', [
                    'error' => $result['message']
                ]);
            }
            $qna = $result['data'];

            $result = FaqService::findAll();
            if (!$result['success']){
                Logger::log("QnaController::index" . $result['message']);
                $this->redirectWithMessage('/', [
                    'error' => $result['message']
                ]);
            }
            $faq = $result['data'];

            $maxPage = ceil(QnaService::getCount()/PAGINATION_SIZE);

            $data = [
                'qna' => $qna,
                'faq' => $faq,
                'qnaPage' => $qnaPage,
                'maxPage' => $maxPage,
            ];

            $this->render('qna/qna', $data);
        }
        echo "TODO";
    }

//    qna/add_question
    public function addQuestion(){
        echo "TODO";
    }
}