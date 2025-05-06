<?php
namespace controller;

use model\ContactMessageModel;
use service\AuthService;
use service\ContactMessageService;
use service\ProductsService;
use const config\ADMIN_CONFIG_URL;
use const config\DEFAULT_IMAGE_NAME;

class UserHomeController extends BaseController {
    public function index(): void
    {
        // read max displayed products from config admin-config.json
        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        $maxDisplayedProducts = $config['maxDisplayedProductsForHomePage'] ?? 16;

        // $newest = ProductsService::getTmp($maxDisplayedProducts);
        $newest = ProductsService::getFilteredProducts([
            'sort' =>  'newest',
            'page' => 1,
            'limit' => $maxDisplayedProducts
        ]);

        $topRated = ProductsService::getFilteredProducts([
            'sort' =>  'top_rated',
            'page' => 1,
            'limit' => $maxDisplayedProducts
        ]);


        $bestSellers = ProductsService::getFilteredProducts([
            'sort' =>  'popular',
            'page' => 1,
            'limit' => $maxDisplayedProducts
        ]);

        $carousel1 = $config['carousel1'] ?? DEFAULT_IMAGE_NAME;
        $carousel2 = $config['carousel2'] ?? DEFAULT_IMAGE_NAME;
        $carousel3 = $config['carousel3'] ?? DEFAULT_IMAGE_NAME;
        $contactNumber = $config['contactNumber'] ?? 'N/A';

        // Render the home page
        $this->render('home/home', [
            'title' => 'Home',
            'newestProducts' => $newest['success'] ? $newest['data']['products'] : [],
            'topRatedProducts' => $topRated['success'] ? $topRated['data']['products'] : [],
            'bestSellers' => $bestSellers['success'] ? $bestSellers['data']['products'] : [],
            'address' => $config['address'] ?? 'N/A',
            'carousel1' => $carousel1,
            'carousel2' => $carousel2,
            'carousel3' => $carousel3,
            'phone' => $contactNumber,
        ]);
    }
}