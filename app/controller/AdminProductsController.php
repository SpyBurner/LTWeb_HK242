<?php
namespace controller;

use core\Controller;
use core\SessionHelper;
use service\ProductsService;
use service\CategoryService;
use service\ManufacturerService;
use core\Logger;
use core\FileManager;
use core\Database;
use core\FileCategory;

class AdminProductsController extends BaseController  {
    public function index() {
        $this->requireAuth(true);

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
        $this->requireAuth(true);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->post('name');
            $price = $this->post('price');
            $description = $this->post('description');
            $stock = $this->post('stock');
            $cateid = $this->post('cateid');
            $mfgid = $this->post('mfgid');
            if (empty($name) || empty($price) || empty($cateid) || empty($mfgid) || empty($stock)) {
                $this->redirectWithMessage('/admin/products', [
                    'error' => 'Please fill in all required fields'
                ]);
                return;
            }

            // Prepare product data with default avatarurl
            $productData = [
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'stock' => $stock,
                'cateid' => $cateid,
                'mfgid' => $mfgid,
                'avatarurl' => 'assets/repo/product',
                'avgrating' => 0,
                'bought' => 0
            ];

            // Save the product with default avatarurl
            $result = ProductsService::save($productData);

            if (!$result['success']) {
                Logger::log("Failed to create product: " . $result['message']);
                $this->redirectWithMessage('/admin/products', [
                    'error' => $result['message']
                ]);
                return;
            }

            // Get the product ID from the database (last inserted ID)
            $productId = Database::getInstance()->getConnection()->lastInsertId();

            // Handle image upload if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                Logger::log("Uploading product image for product: $name (ID: $productId)");
                $image = $_FILES['image'];
                try {
                    $fileResult = FileManager::getInstance()->Save($image, $productId, FileCategory::PRODUCT, true);
                    if (!$fileResult['success']) {
                        Logger::log("Failed to save product image: " . $fileResult['message']);
                        // Product is already created, so we proceed with a warning message
                        $this->redirectWithMessage('/admin/products', [
                            'success' => 'Product created successfully, but failed to upload image: ' . $fileResult['message']
                        ]);
                        return;
                    }

                    Logger::log("Product image saved successfully: " . $fileResult['data']);
                    $avatarurl = $fileResult['data'];

                    // Update the product's avatarurl
                    $updateData = [
                        'productid' => $productId,
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

                    $updateResult = ProductsService::save($updateData);

                    if (!$updateResult['success']) {
                        Logger::log("Failed to update product avatarurl: " . $updateResult['message']);
                        $this->redirectWithMessage('/admin/products', [
                            'success' => 'Product created successfully, but failed to update image: ' . $updateResult['message']
                        ]);
                        return;
                    }

                    Logger::log("Product avatarurl updated successfully for product ID: $productId");
                } catch (\Exception $e) {
                    Logger::log("Error uploading product image: " . $e->getMessage());
                    // Product is already created, so we proceed with a warning message
                    $this->redirectWithMessage('/admin/products', [
                        'success' => 'Product created successfully, but error uploading image: ' . $e->getMessage()
                    ]);
                    return;
                }
            } else if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                Logger::log("File upload error: " . $_FILES['image']['error']);
                // Product is already created, so we proceed with a warning message
                $this->redirectWithMessage('/admin/products', [
                    'success' => 'Product created successfully, but file upload error occurred'
                ]);
                return;
            }

            Logger::log("Product created successfully: $name (ID: $productId)");
            $this->redirectWithMessage('/admin/products', [
                'success' => 'Product created successfully'
            ]);
        } else {
            $this->redirect('/admin/products');
        }
    }

    public function edit($productId = null) {
        $this->requireAuth(true);

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

            if (empty($name) || empty($price) || empty($cateid) || empty($mfgid) || empty($stock)) {
                $this->redirectWithMessage('/admin/products', [
                    'error' => 'Please fill in all required fields'
                ]);
                return;
            }

            // Fetch the current product to retain existing values
            $productResult = ProductsService::findById($productId);
            if (!$productResult['success']) {
                $this->redirectWithMessage('/admin/products', [
                    'error' => 'Product not found'
                ]);
                return;
            }
            $currentProduct = $productResult['data'];

            // Initialize avatarurl with the current value
            $avatarurl = $currentProduct->getAvatarurl();

            // Handle image upload if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                Logger::log("Uploading new product image for product: $name (ID: $productId)");
                $image = $_FILES['image'];
                try {
                    $fileResult = FileManager::getInstance()->Save($image, $productId, FileCategory::PRODUCT, true);
                    if (!$fileResult['success']) {
                        Logger::log("Failed to save product image: " . $fileResult['message']);
                        // Proceed with the update using the existing avatarurl, with a warning
                        $this->redirectWithMessage('/admin/products', [
                            'success' => 'Product updated successfully, but failed to upload image: ' . $fileResult['message']
                        ]);
                        return;
                    }

                    Logger::log("Product image saved successfully: " . $fileResult['data']);
                    $avatarurl = $fileResult['data'];
                } catch (\Exception $e) {
                    Logger::log("Error uploading product image: " . $e->getMessage());
                    // Proceed with the update using the existing avatarurl, with a warning
                    $this->redirectWithMessage('/admin/products', [
                        'success' => 'Product updated successfully, but error uploading image: ' . $e->getMessage()
                    ]);
                    return;
                }
            } else {
                
            }

            // Prepare product data for update
            $productData = [
                'productid' => $productId,
                'name' => $name,
                'price' => $price,
                'description' => $description,
                'stock' => $stock,
                'cateid' => $cateid,
                'mfgid' => $mfgid,
                'avatarurl' => $avatarurl,
                'avgrating' => $currentProduct->getAvgrating(),
                'bought' => $currentProduct->getBought()
            ];

            // Save the product with updated data
            $result = ProductsService::save($productData);

            if (!$result['success']) {
                Logger::log("Failed to update product ID $productId: " . $result['message']);
                $this->redirectWithMessage('/admin/products', [
                    'error' => $result['message']
                ]);
                return;
            }

            Logger::log("Product updated successfully: $name (ID: $productId)");
            $this->redirectWithMessage('/admin/products', [
                'success' => 'Product updated successfully'
            ]);
        } else {
            $this->redirect('/admin/products');
        }
    }

    public function delete($productId = null) {
        $this->requireAuth(true);

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
