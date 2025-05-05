<?php
namespace controller;

use model\ContactMessageModel;
use service\AuthService;
use service\ContactMessageService;
use service\ProductsService;
use const config\ADMIN_CONFIG_URL;

class UserHomeController extends BaseController {
    public function index(): void
    {
        // read max displayed products from config admin-config.json
        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        $maxDisplayedProducts = $config['max_displayed_products'] ?? 16;

        $newest = ProductsService::getTmp($maxDisplayedProducts);
        $topRated = ProductsService::getTmp($maxDisplayedProducts);
        $bestSellers = ProductsService::getTmp($maxDisplayedProducts);

        // Render the home page
        $this->render('home/home', [
            'title' => 'Home',
            'newestProducts' => $newest['success'] ? $newest['data'] : [],
            'topRatedProducts' => $topRated['success'] ? $topRated['data'] : [],
            'bestSellers' => $bestSellers['success'] ? $bestSellers['data'] : [],
            'address' => $config['address'] ?? 'N/A',
        ]);
    }
}