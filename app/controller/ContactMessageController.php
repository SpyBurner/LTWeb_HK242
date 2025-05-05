<?php
namespace controller;

use core\Controller;
use model\ContactMessageModel;
use service\ContactMessageService;

class ContactMessageController extends BaseController
{
    public function submit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Submitted a contact message
            $name = $this->post('name'); // required
            $email = $this->post('email'); // required
            $title = $this->post('title', 'No title');
            $message = $this->post('message', 'No message');

            // Validate the input
            if (empty($name) || empty($email)) {
                $this->redirectWithMessage('/', [
                    'error' => 'Name and email are required.'
                ]);
            }

            // Save the contact message
            $contactMessage = new ContactMessageModel(
                $name,
                $email,
                $title,
                $message,
            );

            $result = ContactMessageService::save($contactMessage);
            if ($result['success']) {
//                return json_encode(['success' => true], ['message' => 'Your message has been sent successfully.']);
                $this->redirectWithMessage('/', [
                    'success' => 'Your message has been sent successfully.'
                ]);
            } else {
//                return json_encode(['success' => false, 'message' => 'Failed to send your message. Please try again later.']);
                $this->redirectWithMessage('/', [
                    'error' => 'Failed to send your message. Please try again later.'
                ]);
            }
        } else {
//            return json_encode(['success' => false, 'message' => 'Invalid request method.']);
            $this->render('error/not-found-404');
        }
    }

    public function index(): void
    {
        $this->requireAuth(true);
        // Render the contact message page
        $this->render('admin/contact-message', [
            'title' => 'Contact Message',
        ]);
    }

    public function getMessages(): void
    {
        $this->requireAuth(true);

        header("Content-Type: application/json");
        $limit = 10;
        $page = $this->get('page', 1);
        $offset = ($page - 1) * $limit;
        $filter = $this->get('filter', 'All');
        $messages = ContactMessageService::findFiltered($filter, $limit, $offset);
        $total = ContactMessageService::countByStatus($filter);

        $readMsgCount = ContactMessageService::countByStatus('Read');
        $unreadMsgCount = ContactMessageService::countByStatus('Unread');
        $repliedMsgCount = ContactMessageService::countByStatus('Replied');
        if ($messages['success'] && $total['success'] && $readMsgCount['success'] && $unreadMsgCount['success'] && $repliedMsgCount['success']) {
            echo json_encode([
                'success' => true,
                'messages' => $messages['data'],
                'total' => $total['data'],
                'page' => $page,
                'limit' => $limit,
                'readCount' => $readMsgCount['data'],
                'unreadCount' => $unreadMsgCount['data'],
                'repliedCount' => $repliedMsgCount['data'],
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch messages.'
            ]);
        }
        exit;
    }

    public function viewMessage($id): void
    {
        $this->requireAuth(true);

        header("Content-Type: application/json");
        $result = ContactMessageService::findById($id);
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch message details.'
            ]);
        }
        exit;
    }

    public function deleteMessage($id): void
    {
        $this->requireAuth(true);

        header("Content-Type: application/json");
        $result = ContactMessageService::deleteById($id);
        if ($result['success']) {
            echo json_encode([
                    'success' => true,
                    'message' => 'Message deleted successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete message.'
            ]);
        }
        exit;
    }

    public function markAsRead($id): void
    {
        $this->requireAuth(true);

        header("Content-Type: application/json");
        $result = ContactMessageService::markAsRead($id);
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Message marked as read successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to mark message as read.'
            ]);
        }
        exit;
    }

    public function markAsReplied($id): void
    {
        $this->requireAuth(true);

        header("Content-Type: application/json");
        $result = ContactMessageService::markAsReplied($id);
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Message marked as replied successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to mark message as replied.'
            ]);
        }
        exit;
    }
}