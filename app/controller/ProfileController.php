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
            $this->redirectWithMessage('/',[
                'error' => $result['message']
            ]);
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
                $this->redirectWithMessage('/profile', ['error' => $result['message']]);
            }

            $this->redirectWithMessage('/profile', ['success' => 'Contact added successfully']);
        }
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
                $this->redirectWithMessage('/profile', ['error' => $result['message']]);
            $this->redirectWithMessage('/profile', ['success' => 'Contact updated successfully']);
        }
    }

    public function deleteContact(){
        $this->requireAuth();
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'GET'){
            $contactid = $this->get('contactid');
            $result = ContactService::deleteById($contactid);
            if (!$result['success']) {
                $this->redirectWithMessage('/profile', ['error' => $result['message']]);
            }
            $this->redirectWithMessage('/profile', ['success' => 'Contact deleted successfully']);
        }
    }

    public function updateAvatar_API(){
        $user = AuthService::getCurrentUser()['user'];

        Logger::log("Update avatar API called");

        if ($user->getIsAdmin()){
            $this->redirectWithMessage('/profile', ['error' => 'Admin cannot update avatar :(']);
        }

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST'){

            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                Logger::log("File upload error: " . $_FILES['file']['error']);
                $this->redirectWithMessage('/profile', ['error' => 'File upload error']);
            }
            $avatar = $_FILES['file'];
            $result = CustomerService::updateAvatar($avatar, $user->getUserid());
            if (!$result['success']){
                Logger::log("Update avatar failed: " . $result['message']);
                $this->redirectWithMessage('/profile', ['error' => $result['message']]);
            }
            Logger::log("Update avatar success: " . $result['message']);
            $this->redirectWithMessage('/profile', ['success' => 'Avatar updated successfully']);
        }
    }

    public function changePassword(){
        $this->requireAuth();

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST'){
            $oldPassword = $this->post('old_password');
            $newPassword = $this->post('new_password');
            $confirmPassword = $this->post('confirm_password');

            if ($newPassword !== $confirmPassword){
                Logger::log("New password and confirm password do not match");
                $this->redirectWithMessage('/profile', ['error' => 'New password and confirm password do not match']);
            }

            $result = AuthService::changePassword($oldPassword, $newPassword);
            if (!$result['success']){
                $this->redirectWithMessage('/profile', ['error' => $result['message']]);
            }
            AuthService::logout();
            $this->redirectWithMessage('/account/login', ['success' => 'Password changed successfully, please login again']);
        }
    }

    public function test_deleteUser(){
        $this->requireAuth();
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'GET'){
            $userid = $this->get('userid');
            $result = UserService::deleteById($userid);
            if (!$result['success']) {
                $this->redirectWithMessage('/profile', ['error' => $result['message']]);
            }
            $this->redirectWithMessage('/profile', ['success' => 'User deleted successfully']);
        }

    }
}