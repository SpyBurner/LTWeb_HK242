<?php
namespace controller;

use core\Controller;
use core\Logger;
use model\MessageModel;
use model\QnaEntryModel;
use service\AuthService;
use service\CustomerService;
use service\FaqService;
use service\MessageService;
use service\QnaService;
use service\UserService;
use const config\DEFAULT_MOD_AVATAR_URL;

class QnaController extends Controller {
    public function index() {
        /* Needs:
         * qna list, paginated
         * faq list
         * */
        $search = $this->get('search');

        $qnaid = $this->get('qnaid');

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

            // When user clicks on an qna entry, show the question and its messages
            if ($qnaid != null){
                $result = MessageService::findallbyqnaid($qnaid);
                if (!$result['success']){
                    Logger::log("QnaController::index" . $result['message']);
                    $this->redirectWithMessage('/qna', [
                        'error' => $result['message']
                    ]);
                }
                $msgs = $result['data'];

                $msgs_data = [];
                for ($index = 0; $index < count($msgs); $index++){
                    $msg = $msgs[$index];

                    $result = UserService::findById($msg->getUserid());
                    if (!$result['success']){
                        Logger::log("QnaController::index" . $result['message']);
                        $this->redirectWithMessage('/qna', [
                            'error' => $result['message']
                        ]);
                    }
                    $msg_user = $result['data'];

                    if (!$msg_user->getIsadmin()){
                        $result = CustomerService::findbyid($msg_user->getUserid());

                        if (!$result['success']){
                            Logger::log("QnaController::index" . $result['message']);
                            $this->redirectWithMessage('/qna', [
                                'error' => $result['message']
                            ]);
                        }
                        $msg_avatar = $result['data']->getAvatarurl();
                    }
                    else{
                        $msg_avatar = DEFAULT_MOD_AVATAR_URL;
                    }

                    $msgs_data[$index] = [
                        'msg' => $msg,
                        'user' => $msg_user,
                        'avatar' => $msg_avatar,
                    ];
                }


                $data['msgs_data'] = $msgs_data;
            }

            $this->render('qna/qna', $data);
        }
    }

//    qna/add_question
    public function addQuestion(){
        $this->requireAuth();

        $getUserResult = AuthService::getCurrentUser();
        $user = $getUserResult['user'];

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST'){
            $userid = $user->getUserid();
            $question = $this->post('question');

            if ($question == null){
                $this->redirectWithMessage('/qna', [
                    'error' => 'Question cannot be empty'
                ]);
            }
            Logger::log("QnaController::addQuestion " . $question);
            Logger::log("QnaController::addQuestion " . $userid);

            $message = new MessageModel(null, null, $question, null, $userid);

            $qnaEntry = new QnaEntryModel(null, $message);

            $result = QnaService::save($qnaEntry);

            if (!$result['success']){
                Logger::log("QnaController::addQuestion " . $result['message']);
                $this->redirectWithMessage('/qna', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/qna', [
                'success' => 'Question added successfully'
            ]);
        }

    }

    public function addMessage(){
        $this->requireAuth();

        $getUserResult = AuthService::getCurrentUser();
        $user = $getUserResult['user'];

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST') {
            $userid = $user->getUserid();
            $qnaid = $this->post('qnaid');
            $message = $this->post('message');

            if ($message == null) {
                $this->redirectWithMessage('/qna#qna', [
                    'error' => 'Message cannot be empty'
                ]);
            }

            $model = new MessageModel(null, null, $message, $qnaid, $userid);

            $result = MessageService::save($model);
            if (!$result['success']) {
                Logger::log("QnaController::addMessage " . $result['message']);
                $this->redirectWithMessage('/qna?qnaid=' . $qnaid . '#qna', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/qna?qnaid=' . $qnaid . '#qna', [
                'success' => 'Message added successfully'
            ]);
        }
    }

//    public function qnaDetail_API(){
//        $this->requireAuth();
//
//        $qnaid = $this->get('qnaid');
//
//        $ret = [];
//
//        if ($qnaid == null) {
//            echo json_encode($ret);
//            return;
//        }
//
//        $result = MessageService::findAllByQnaId($qnaid);
//        if (!$result['success']){
//            Logger::log("QnaController::qnaDetail_API " . $result['message']);
//            $ret['success'] = false;
//            $ret['message'] = $result['message'];
//
//            echo json_encode($ret);
//            return;
//        }
//        $ret['success'] = true;
//
//        $msgs = $result['data'];
//
//        foreach ($msgs as $msg){
//            $ret['data'][] = MessageModel::toArray($msg);
//        }
//        echo json_encode($ret);
//    }
}