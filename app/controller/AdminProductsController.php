<?php
namespace controller;

use core\Controller;
use core\SessionHelper;
use service\ProductsService;
use service\CategoryService;
use service\ManufacturerService;

class AdminProductsController extends Controller {
    public function index() {
        // Get filter parameters from request
        $filters = [
            'search' => $this->get('search'),
            'category' => $this->get('category')
        ];

        // Get all products with optional filters
        $productsResult = ProductsService::findAll($filters);
        
        // Get all categories and manufacturers for dropdowns
        $categoriesResult = CategoryService::findAll();
        $manufacturersResult = ManufacturerService::findAll();

        $this->render('admin/products', [
            'products' => $productsResult['success'] ? $productsResult['data'] : [],
            'categories' => $categoriesResult['success'] ? $categoriesResult['data'] : [],
            'manufacturers' => $manufacturersResult['success'] ? $manufacturersResult['data'] : [],
            'messages' => SessionHelper::getFlash('messages') ?? [],
            'searchTerm' => $filters['search'] ?? '',
            'selectedCategory' => $filters['category'] ?? ''
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->post('name');
            $price = $this->post('price');
            $description = $this->post('description');
            $stock = $this->post('stock');
            $cateid = $this->post('cateid');
            $mfgid = $this->post('mfgid');
    
            $avatarurl = '/assets/images/default-product.png';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/products/';
                $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
                $uploadPath = $uploadDir . $fileName;
    
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $avatarurl = '/uploads/products/' . $fileName;
                }
            }
    
            if (empty($name) || empty($price) || empty($cateid) || empty($mfgid) || empty($stock)) {
                $this->redirectWithMessage('/admin/products', [
                    'error' => 'Please fill in all required fields'
                ]);
                return;
            }
    
            $productData = [
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'stock' => $stock,
                'cateid' => $cateid,
                'mfgid' => $mfgid,
                'avatarurl' => $avatarurl,
                'avgrating' => 0,
                'bought' => 0
            ];
    
            $result = ProductsService::save($productData);
    
            if ($result['success']) {
                $this->redirectWithMessage('/admin/products', [
                    'success' => 'Product created successfully'
                ]);
            } else {
                $this->redirectWithMessage('/admin/products', [
                    'error' => $result['message']
                ]);
            }
        } else {
            $this->redirect('/admin/products');
        }
    }

    public function edit($productId = null) {
        if (empty($productId)) {
            $this->redirectWithMessage('/admin/products', [
                'error' => 'Invalid product ID'
            ]);
            return;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->post('name');
            $price = $this->post('price');
            $description = $this->post('description');
            $stock = $this->post('stock');
            $cateid = $this->post('cateid');
            $mfgid = $this->post('mfgid');
    
            // Lấy thông tin sản phẩm hiện tại để giữ các giá trị cũ nếu không thay đổi ảnh
            $productResult = ProductsService::findById($productId);
            if (!$productResult['success']) {
                $this->redirectWithMessage('/admin/products', [
                    'error' => 'Product not found'
                ]);
                return;
            }
            $currentProduct = $productResult['data'];
            $avatarurl = $currentProduct->getAvatarurl();
    
            // Xử lý upload ảnh nếu có
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/products/';
                $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
                $uploadPath = $uploadDir . $fileName;
    
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $avatarurl = '/uploads/products/' . $fileName;
                }
            }
    
            if (empty($name) || empty($price) || empty($cateid) || empty($mfgid) || empty($stock)) {
                $this->redirectWithMessage('/admin/products', [
                    'error' => 'Please fill in all required fields'
                ]);
                return;
            }
    
            // Chuẩn bị dữ liệu để cập nhật
            $productData = [
                'productid' => $productId,
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'stock' => $stock,
                'cateid' => $cateid,
                'mfgid' => $mfgid,
                'avatarurl' => $avatarurl,
                'avgrating' => $currentProduct->getAvgrating(), // Giữ nguyên giá trị cũ
                'bought' => $currentProduct->getBought()        // Giữ nguyên giá trị cũ
            ];
    
            $result = ProductsService::save($productData);
    
            if ($result['success']) {
                $this->redirectWithMessage('/admin/products', [
                    'success' => 'Product updated successfully'
                ]);
            } else {
                $this->redirectWithMessage('/admin/products', [
                    'error' => $result['message']
                ]);
            }
        } else {
            $this->redirect('/admin/products');
        }
    }

    public function delete($productId = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($productId)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
                exit;
            }

            $result = ProductsService::deleteById($productId);

            if ($result['success']) {
                $this->redirectWithMessage('/admin/products', [
                    'success' => 'Product deleted successfully'
                ]);
            } else {
                $this->redirectWithMessage('/admin/products', [
                    'error' => $result['message']
                ]);
            }
        } else {
            $this->redirectWithMessage('/admin/products', [
                'error' => 'Invalid request method'
            ]);
        }
    }

    // View chi tiết sản phẩm (chuyển hướng đến trang chi tiết công khai)
    public function view($productId = null) {
        if (empty($productId)) {
            $this->redirectWithMessage('/admin/products', [
                'error' => 'Invalid product ID'
            ]);
            return;
        }
        $this->redirect("/products/detail/$productId");
    }
}