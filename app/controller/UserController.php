<?php
namespace controller;

use core\Controller;
use service\AuthService;
use service\CategoryService;

class UserController extends Controller {
    protected array $headerData;

    public function __construct() {
        $this->initializeHeaderData();
    }

    protected function initializeHeaderData(): void
    {
        $categories = CategoryService::findAll();
        $this->headerData['categories'] = $categories['success'] ? $categories['data'] : [];

        $user = AuthService::getCurrentUser();
        if ($user['success']){
            $this->headerData['isLoggedIn'] = true;
            $this->headerData['isAdmin'] = $user['user']->getIsadmin();
            $this->headerData['username'] = $user['user']->getUsername();
            $this->headerData['avatar'] = $user['avatar'];
        } else {
            $this->headerData['isLoggedIn'] = false;
            $this->headerData['isAdmin'] = false;
            $this->headerData['username'] = null;
            $this->headerData['avatar'] = null;
        }
    }

    protected function render($view, $data = []): void
    {
        // Merge header data with view data
        $data = array_merge($this->headerData, $data);

        // Render the view
        parent::render($view, $data);
    }
}