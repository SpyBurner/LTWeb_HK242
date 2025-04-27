<?php
namespace controller;

use core\Controller;
use core\Logger;
use model\ContactModel;
use service\AuthService;
use service\ContactService;

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
            $customerId = $this->post('customerId');

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
            $contactId = $this->post('contactId');
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
}