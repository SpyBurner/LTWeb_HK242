<?php
namespace controller;

use core\Controller;
use const config\ADMIN_CONFIG_URL;
use const config\DEFAULT_IMAGE_NAME;

class AboutController extends BaseController {
    public function index() : void
    {
        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);

        // Render the about page
        $this->render('about/about', [
            'title' => 'About Us',
            'slogan' => $config['slogan'] ?? 'N/A',
            'aboutUs' => $config['aboutUs'] ?? 'N/A',
            'partners' => $config['partners'] ?? [],
            'banner' => $config['banner'] ?? DEFAULT_IMAGE_NAME,
        ]);
    }
}