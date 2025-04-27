<?php
namespace controller;

use core\Controller;
use core\Logger;
use model\ContactModel;
use service\AuthService;
use service\ContactService;
use service\CustomerService;
use service\UserService;

class ProfileController extends Controller {
    public function index() {
        $this->requireAuth();

        // User
        $result = AuthService::getCurrentUser();
        if (!$result['success']){
            $this->redirectWithMessage('/', $result['message']);
        }

        $user = $result['user'];

        //Avatar
        $avatar = $result['avatar'];

        // Contact
        $result = ContactService::findAllByCustomerId($user->getUserid());
        $contacts = $result['success'] ? $result['data'] : null;

        require_once __DIR__ . "/../view/profile/profile.php";
    }

    public function addContact(){
        $this->requireAuth();

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST'){
            $name = $this->post('name');
            $phone = $this->post('phone');
            $address = $this->post('address');
            $customerId = $this->post('customerid');

            $model = new ContactModel(null, $name, $phone, $address, $customerId);

            $result = ContactService::save($model);
            if (!$result['success']){
                $this->redirectWithMessage('/profile', $result['message']);
            }

            $this->redirectWithMessage('/profile', 'Contact added successfully');
        }
        Logger::log("Add contact fall through???");
        $this->redirectWithMessage('/profile', 'Contact add have fallen through');
    }

    public function editContact(){
        $this->requireAuth();

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST'){
            $contactId = $this->post('contactid');
            $name = $this->post('name');
            $phone = $this->post('phone');
            $address = $this->post('address');
            $customerId = $this->post('customerid');

            $model = new ContactModel($contactId, $name, $phone, $address, $customerId);
            $result = ContactService::save($model);
            if (!$result['success'])
                $this->redirectWithMessage('/profile', $result['message']);
            $this->redirectWithMessage('/profile', 'Contact updated successfully');
        }
    }

    public function deleteContact(){
        $this->requireAuth();
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'GET'){
            $contactid = $this->get('contactid');
            $result = ContactService::deleteById($contactid);
            if (!$result['success']) {
                $this->redirectWithMessage('/profile', $result['message']);
            }
            $this->redirectWithMessage('/profile', 'Contact deleted successfully');
        }
    }

    public function updateAvatar_API(){
        $user = AuthService::getCurrentUser()['user'];

        Logger::log("Update avatar API called");

        if ($user->getIsAdmin()){
            $this->redirectWithMessage('/profile', 'Admin cannot update avatar :(');
        }

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST'){

            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                Logger::log("File upload error: " . $_FILES['file']['error']);
                $this->redirectWithMessage('/profile', 'File upload error');
            }
            $avatar = $_FILES['file'];
            $result = CustomerService::updateAvatar($avatar, $user->getUserid());
            if (!$result['success']){
                Logger::log("Update avatar failed: " . $result['message']);
                $this->redirectWithMessage('/profile', $result['message']);
            }
            Logger::log("Update avatar success: " . $result['message']);
            $this->redirectWithMessage('/profile', 'Avatar updated successfully');
        }
        Logger::log("Update avatar fall through???");
        $this->redirectWithMessage('/profile', 'Avatar update have fallen through');
    }

    public function test_deleteUser(){
        $this->requireAuth();
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'GET'){
            $userid = $this->get('userid');
            $result = UserService::deleteById($userid);
            if (!$result['success']) {
                $this->redirectWithMessage('/profile', $result['message']);
            }
            $this->redirectWithMessage('/profile', 'User deleted successfully');
        }

    }
}