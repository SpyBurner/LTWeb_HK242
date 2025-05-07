<?php
namespace controller;

use core\Logger;
use http\Message;
use model\QnaEntryModel;
use model\QnaModel;
use service\MessageService;
use service\QnaService;

class AdminQnaController extends BaseController {
    public function index() {
        $this->requireAuth(true);
        $data = [];

        $edit_id = $this->get('edit');

        $edit_qna_entry = null;
        $edit_qna_messages = null;
        if ($edit_id != null) {
            $result = QnaService::findById($edit_id);
            if (!$result['success']) {
                Logger::log('Failed to fetch Q&A entry: ' . $result['message']);
                $this->redirectWithMessage('/admin/qna', [
                    'error' => $result['message']
                ]);
            }

            $edit_qna_entry = $result['data'];

            $result = MessageService::findAllByQnaId($edit_id);
            if (!$result['success']) {
                Logger::log('Failed to fetch messages for Q&A entry: ' . $result['message']);
                $this->redirectWithMessage('/admin/qna', [
                    'error' => $result['message']
                ]);
            }
            $edit_qna_messages = $result['data'];
        }

        if ($edit_qna_entry != null)
            $data['edit_qna_entry'] = $edit_qna_entry;
        if ($edit_qna_messages != null)
            $data['edit_qna_messages'] = $edit_qna_messages;

        $result = QnaService::findAll();
        if (!$result['success']) {
            Logger::log('Failed to fetch Q&A entries: ' . $result['message']);
            $this->redirectWithMessage('/admin/', [
                'error' => $result['message']
            ]);
        }

        $data['qna_list'] = $result['data'];

        $this->render('admin/qna', $data);
    }

    public function deleteQna() {
        $this->requireAuth(true);

        $qnaid = $this->get('id');

        if ($qnaid == null) {
            Logger::log('Delete Q&A failed: qnaid not provided.');
            $this->redirectWithMessage('/admin/qna', [
                'error' => 'Q&A ID not provided.'
            ]);
        }

        // Delete the Q&A entry
        $result = QnaService::deleteById($qnaid);
        if (!$result['success']) {
            Logger::log("Failed to delete Q&A ID $qnaid: " . $result['message']);
            $this->redirectWithMessage('/admin/qna', [
                'error' => 'Failed to delete Q&A: ' . $result['message']
            ]);
        }

        Logger::log("Successfully deleted Q&A ID $qnaid.");
        $this->redirectWithMessage('/admin/qna', [
            'success' => 'Q&A deleted successfully.'
        ]);
    }


    public function deleteMessage() {
        $this->requireAuth(true);

        $msgid = $this->get('id'); // for GET-based deletion

        if ($msgid == null) {
            Logger::log('Delete message failed: msgid not provided.');
            $this->redirectWithMessage('/admin/qna', [
                'error' => 'Message ID not provided.'
            ]);
        }

        $result = MessageService::deleteById($msgid);
        if (!$result['success']) {
            Logger::log('Failed to delete message ID ' . $msgid . ': ' . $result['message']);
            $this->redirectWithMessage('/admin/qna', [
                'error' => 'Failed to delete message: ' . $result['message']
            ]);
        }

        Logger::log('Successfully deleted message ID ' . $msgid);
        $this->redirectWithMessage('/admin/qna', [
            'success' => 'Message deleted successfully.'
        ]);
    }


}
