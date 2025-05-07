<?php
namespace controller;

use core\Logger;
use model\FAQEntryModel;
use service\FaqService;

class AdminFAQController extends BaseController {
    public function index(){
        $this->requireAuth(true);
        $data = [];

        $edit_id = $this->get('edit');
        $edit_faq_entry = null;
        if ($edit_id != null){
            $result = FaqService::findById($edit_id);
            if (!$result['success']) {
                Logger::log('Failed to fetch FAQ entry: ' . $result['message']);
                $this->redirectWithMessage('admin/faq', [
                    'error' => $result['message']
                ]);
            }
            $edit_faq_entry = $result['data'];
        }

        if ($edit_faq_entry != null)
            $data['edit_faq_entry'] = $edit_faq_entry;

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

    public function editFaq(){
        $this->requireAuth(true);

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST'){
            $id = $this->post('id');
            $question = $this->post('question');
            $answer = $this->post('answer');

            if (empty($question) || empty($answer)){
                $this->redirectWithMessage('/admin/faq', [
                    'error' => 'Please fill in all fields'
                ]);
            }

            $model = new FAQEntryModel($id, $answer, $question);

            $result = FaqService::save($model);
            if (!$result['success']) {
                $this->redirectWithMessage('/admin/faq', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/admin/faq', [
                'success' => 'FAQ updated successfully'
            ]);
        }

        $this->redirectWithMessage('/admin/faq', []);
    }

    public function deleteFaq(){
        $this->requireAuth(true);

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'GET'){
            $id = $this->get('id');

            if (empty($id)){
                $this->redirectWithMessage('/admin/faq', [
                    'error' => 'Please fill in all fields'
                ]);
            }

            $result = FaqService::deleteById($id);
            if (!$result['success']) {
                $this->redirectWithMessage('/admin/faq', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/admin/faq', [
                'success' => 'FAQ deleted successfully'
            ]);
        }

        $this->redirectWithMessage('/admin/faq', [
            'error' => 'FAQ deletion fall-through'
        ]);
    }

}