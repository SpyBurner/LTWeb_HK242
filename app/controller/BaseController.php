<?php
namespace controller;

use core\Controller;
use service\AuthService;
use service\CategoryService;
use const config\ADMIN_CONFIG_URL;
use const config\DEFAULT_IMAGE_NAME;

class BaseController extends Controller {
    protected array $headerData;

    public function __construct() {
        $this->initializeHeaderData();
    }

    protected function initializeHeaderData(): void
    {
        $categories = CategoryService::findAll();
        $this->headerData['header_categories'] = $categories['success'] ? $categories['data'] : [];
        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        $this->headerData['header_logo'] = $config['logo'] ?? DEFAULT_IMAGE_NAME;

        $user = AuthService::getCurrentUser();
        if ($user['success']){
            $this->headerData['header_isLoggedIn'] = true;
            $this->headerData['header_isAdmin'] = $user['user']->getIsadmin();
            $this->headerData['header_username'] = $user['user']->getUsername();
            $this->headerData['header_avatar'] = $user['avatar'];
        } else {
            $this->headerData['header_isLoggedIn'] = false;
            $this->headerData['header_isAdmin'] = false;
            $this->headerData['header_username'] = '';
            $this->headerData['header_avatar'] = '';
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