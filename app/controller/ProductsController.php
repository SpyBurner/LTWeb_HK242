<?php
namespace controller;

use core\Controller;
use core\SessionHelper;
use service\ProductsService;
use service\CategoryService;
use service\ManufacturerService;
use service\RateProductService;

class ProductsController extends BaseController  {
    public function index() {
        // Get filter parameters from request
        $filters = [
            'category' => $this->get('category'),
            'manufacturer' => $this->get('manufacturer'),
            'price_range' => $this->get('price_range'),
            'search' => $this->get('search'), 
            'sort' => $this->get('sort', 'newest'),
            'page' => max(1, (int)$this->get('page', 1)), 
            'limit' => 24 
        ];



        // Get filtered products with pagination
        $productsResult = ProductsService::getFilteredProducts($filters);
        
        // Get all categories and manufacturers for filters
        $categories = CategoryService::findAll();
        $manufacturers = ManufacturerService::findAll();

        $this->render('products/products', [
            'products' => $productsResult['success'] ? $productsResult['data']['products'] : [],
            'categories' => $categories['success'] ? $categories['data'] : [],
            'manufacturers' => $manufacturers['success'] ? $manufacturers['data'] : [],
            'filters' => $filters,
            'totalPages' => $productsResult['success'] ? $productsResult['data']['totalPages'] : 1,
            'currentPage' => $filters['page'],
            'error' => $productsResult['success'] ? null : $productsResult['message'],
            'messages' => SessionHelper::getFlash('messages') ?? []
        ]);
    }

    // Các phương thức khác giữ nguyên
    public function detail($id) {
        try {
            $product = ProductsService::findById($id);
            if (!$product['success']) {
                $this->redirectWithMessage('/products', ['error' => 'Product not found']);
                return;
            }
    
            $reviews = RateProductService::findById($id);
            
            $avgRating = 0;
            $ratingCount = 0;
            $ratingStats = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            
            if ($reviews['success'] && !empty($reviews['data'])) {
                $ratingCount = count($reviews['data']);
                $totalRating = 0;
                
                foreach ($reviews['data'] as $review) {
                    $rating = $review->getRating();
                    $totalRating += $rating;
                    $ratingStats[$rating]++;
                }
                
                $avgRating = $totalRating / $ratingCount;
            }
            $product['data']->setAvgrating($avgRating);    
            $relatedProducts = ProductsService::getRelatedProducts($id, 4);
    
            $this->render('product-detail/product-detail', [
                'product' => $product['data'],
                'reviews' => $reviews['success'] ? $reviews['data'] : [],
                'ratingStats' => $ratingStats,
                'avgRating' => $avgRating,
                'ratingCount' => $ratingCount,
                'relatedProducts' => $relatedProducts['success'] ? $relatedProducts['data'] : [],
                'messages' => SessionHelper::getFlash('messages') ?? []
            ]);
        } catch (\Exception $e) {
            $this->redirectWithMessage('/products', ['error' => 'Error loading product details']);
        }
    }

    public function create() {
    }

    public function edit() {
    }

    public function delete() {
    
    }

    public function view($id) {
        $product = ProductsService::findById($id);
        if ($product['success']) {
            $this->render('products/view', [
                'product' => $product['data']
            ]);
        } else {
            $this->redirectWithMessage('/products', [
                'error' => $product['message']
            ]);
        }
    }

    protected function getQueryString($exclude = []) {
        $query = $_GET;
        foreach ($exclude as $key) {
            unset($query[$key]);
        }
        return $query ? '&' . http_build_query($query) : '';
    }
}