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
            'search' => $this->get('search'), // Thêm search vào filters
            'sort' => $this->get('sort', 'newest'),
            'page' => max(1, (int)$this->get('page', 1)), // Đảm bảo page >= 1
            'limit' => 24 // Cố định 24 sản phẩm mỗi trang
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
<<<<<<< HEAD
    }

    public function edit() {
    }

    public function delete() {
    
=======
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productData = [
                'name' => $this->post('name'),
                'price' => $this->post('price'),
                'description' => $this->post('description'),
                'mfgid' => $this->post('mfgid'),
                'stock' => $this->post('stock'),
                'cateid' => $this->post('cateid'),
                'avatarurl' => $this->post('avatarurl')
            ];

            $product = new \model\ProductModel(
                $productData['name'],
                $productData['price'],
                $productData['description'],
                $productData['mfgid'],
                $productData['stock'],
                $productData['cateid'],
                $productData['avatarurl']
            );

            $result = ProductsService::save($product);

            if ($result['success']) {
                $this->redirectWithMessage('/products', [
                    'success' => 'Product created successfully!'
                ]);
            } else {
                SessionHelper::setFlash('form_data', $productData);
                $this->redirectWithMessage('/products/create', [
                    'error' => $result['message']
                ]);
            }
        } else {
            $this->render('products/create', [
                'form_data' => SessionHelper::getFlash('form_data')
            ]);
        }
    }

    public function edit($id) {
        $product = ProductsService::findById($id);

        if (!$product['success']) {
            $this->redirectWithMessage('/products', [
                'error' => 'Product not found'
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productData = [
                'name' => $this->post('name'),
                'price' => $this->post('price'),
                'description' => $this->post('description'),
                'mfgid' => $this->post('mfgid'),
                'stock' => $this->post('stock'),
                'cateid' => $this->post('cateid'),
                'avatarurl' => $this->post('avatarurl')
            ];

            $productModel = $product['data'];
            $productModel->setName($productData['name']);
            $productModel->setPrice($productData['price']);
            $productModel->setDescription($productData['description']);
            $productModel->setMfgid($productData['mfgid']);
            $productModel->setStock($productData['stock']);
            $productModel->setCateid($productData['cateid']);
            $productModel->setAvatarurl($productData['avatarurl']);

            $result = ProductsService::save($productModel);

            if ($result['success']) {
                $this->redirectWithMessage('/products', [
                    'success' => 'Product updated successfully!'
                ]);
            } else {
                SessionHelper::setFlash('form_data', $productData);
                $this->redirectWithMessage("/products/edit/$id", [
                    'error' => $result['message']
                ]);
            }
        } else {
            $this->render('products/edit', [
                'product' => $product['data'],
                'form_data' => SessionHelper::getFlash('form_data') ?? ProductsService::toArray($product['data'])
            ]);
        }
    }

    public function delete($id) {
        $result = ProductsService::deleteById($id);
        $this->redirectWithMessage('/products', [
            $result['success'] ? 'success' : 'error' => $result['message']
        ]);
>>>>>>> php/home
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