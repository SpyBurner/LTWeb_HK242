<?php
namespace controller;

use core\Logger;
use model\FAQEntryModel;
use service\FaqService;

class AdminFAQController extends BaseController {
    public function index(){
        $this->requireAuth(true);
        $data = [];

        $result = FaqService::findAll();
        if (!$result['success']) {
            Logger::log('Failed to fetch FAQs: ' . $result['message']);
            $this->redirectWithMessage('admin/', [
                'error' => $result['message']
            ]);
        }
        $faq_list = $result['data'];

        $data['faq_list'] = $faq_list;

        $this->render('admin/faq', $data);
    }

    public function addFaq(){
        $this->requireAuth(true);

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST'){
            $question = $this->post('question');
            $answer = $this->post('answer');

            if (empty($question) || empty($answer)){
                $this->redirectWithMessage('/admin/faq', [
                    'error' => 'Please fill in all fields'
                ]);
            }

            $model = new FAQEntryModel(null, $question, $answer);

            $result = FaqService::save($model);
            if (!$result['success']) {
                $this->redirectWithMessage('/admin/faq', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/admin/faq', [
                'success' => 'FAQ added successfully'
            ]);
        }

        $this->redirectWithMessage('/admin/faq', []);
    }
}