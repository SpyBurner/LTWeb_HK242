<?php
namespace controller;

use core\Controller;
use service\AuthService;
use service\ContactService;

class ProfileController extends Controller {
    public function index() {
        $this->requireAuth();

        $result = AuthService::getCurrentUser();
        if (!$result['success']){
            $this->redirectWithMessage('/', $result['message']);
        }

        $user = $result['user'];

        $result = ContactService::findAllByCustomerId($user->getUserid());
        if (!$result['success']){
            $this->redirectWithMessage('/profile', $result['message']);
        }

        $contacts = $result['data'];

        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT PROFILECONTROLLER";
        }

        require_once __DIR__ . "/../view/profile/profile.php";
    }
}