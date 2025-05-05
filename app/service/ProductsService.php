<?php
namespace service;

use core\Database;
use core\IService;
use model\ProductModel;
use Exception;
use function core\handleException;

class ProductsService implements IService {
    public static function getFilteredProducts($filters = []) {
        try {
            $pdo = Database::getInstance()->getConnection();
            
            // Base query
            $query = "SELECT p.*, m.name as manufacturer_name, c.name as category_name 
                     FROM Product p
                     LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid
                     LEFT JOIN Category c ON p.cateid = c.cateid
                     WHERE 1=1";
            
            // Count query for pagination
            $countQuery = "SELECT COUNT(*) as total FROM Product p
                          LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid
                          LEFT JOIN Category c ON p.cateid = c.cateid
                          WHERE 1=1";
            
            $params = [];
            $countParams = [];
            
            // Thêm chức năng search - SỬA LẠI PHẦN NÀY
            if (!empty($filters['search'])) {
                $query .= " AND (p.name LIKE :search_name OR p.description LIKE :search_desc)";
                $countQuery .= " AND (p.name LIKE :search_name OR p.description LIKE :search_desc)";
                
                $searchTerm = '%' . $filters['search'] . '%';
                $params[':search_name'] = $searchTerm;
                $params[':search_desc'] = $searchTerm;
                $countParams[':search_name'] = $searchTerm;
                $countParams[':search_desc'] = $searchTerm;
            }
            
            // Apply category filter
            if (!empty($filters['category'])) {
                $query .= " AND p.cateid = :cateid";
                $countQuery .= " AND p.cateid = :cateid";
                $params[':cateid'] = $filters['category'];
                $countParams[':cateid'] = $filters['category'];
            }
            
            // Apply manufacturer filter
            if (!empty($filters['manufacturer'])) {
                $query .= " AND p.mfgid = :mfgid";
                $countQuery .= " AND p.mfgid = :mfgid";
                $params[':mfgid'] = $filters['manufacturer'];
                $countParams[':mfgid'] = $filters['manufacturer'];
            }
            
            // Apply price range filter
            if (!empty($filters['price_range'])) {
                switch ($filters['price_range']) {
                    case 'under_100k':
                        $query .= " AND p.price < 100000";
                        $countQuery .= " AND p.price < 100000";
                        break;
                    case '100k_to_200k':
                        $query .= " AND p.price >= 100000 AND p.price <= 200000";
                        $countQuery .= " AND p.price >= 100000 AND p.price <= 200000";
                        break;
                    case '200k_to_500k':
                        $query .= " AND p.price > 200000 AND p.price <= 500000";
                        $countQuery .= " AND p.price > 200000 AND p.price <= 500000";
                        break;
                    case 'over_500k':
                        $query .= " AND p.price > 500000";
                        $countQuery .= " AND p.price > 500000";
                        break;
                }
            }
            
            // Apply sorting
            switch ($filters['sort'] ?? 'newest') {
                case 'price_asc':
                    $query .= " ORDER BY p.price ASC";
                    break;
                case 'price_desc':
                    $query .= " ORDER BY p.price DESC";
                    break;
                case 'popular':
                    $query .= " ORDER BY p.bought DESC";
                    break;
                case 'newest':
                default:
                    $query .= " ORDER BY p.productid DESC";
            }
            
            // Get total count first - SỬA LẠI CÁCH BIND PARAM
            $countStmt = $pdo->prepare($countQuery);
            foreach ($countParams as $key => $value) {
                $countStmt->bindValue($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
            $countStmt->execute();
            $totalItems = (int)$countStmt->fetch()['total'];
            
            // Calculate pagination
            $itemsPerPage = $filters['limit'] ?? 24;
            $currentPage = $filters['page'] ?? 1;
            $totalPages = max(1, ceil($totalItems / $itemsPerPage));
            $offset = ($currentPage - 1) * $itemsPerPage;
            
            // Add pagination to main query
            $query .= " LIMIT :limit OFFSET :offset";
            
            // Prepare main query with updated query string
            $stmt = $pdo->prepare($query);
            
            // Bind filter parameters - SỬA LẠI CÁCH BIND PARAM
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
            
            // Bind pagination parameters separately
            $stmt->bindValue(':limit', (int)$itemsPerPage, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
            
            $stmt->execute();
            
            $products = array_map(function($row) {
                $product = ProductModel::toObject($row);
                $product->manufacturerName = $row['manufacturer_name'];
                $product->categoryName = $row['category_name'];
                return $product;
            }, $stmt->fetchAll());
            
            return [
                'success' => true,
                'data' => [
                    'products' => $products,
                    'totalPages' => $totalPages,
                    'totalItems' => $totalItems,
                    'currentPage' => $currentPage
                ]
            ];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    // Các phương thức khác giữ nguyên
    public static function save($data) {
        try {
            $pdo = Database::getInstance()->getConnection();

            // Kiểm tra dữ liệu đầu vào
            if (!is_array($data)) {
                return ['success' => false, 'message' => 'Invalid data format. Array expected.'];
            }

            // Chuẩn bị dữ liệu
            $requiredFields = ['name', 'price', 'stock', 'cateid', 'mfgid'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return ['success' => false, 'message' => "Missing or empty required field: $field"];
                }
            }

            // Giá trị mặc định nếu không có
            $data['description'] = $data['description'] ?? '';
            $data['avatarurl'] = $data['avatarurl'] ?? '/assets/images/default-product.png';
            $data['avgrating'] = $data['avgrating'] ?? 0;
            $data['bought'] = $data['bought'] ?? 0;
            $data['productid'] = $data['productid'] ?? null;

            if (empty($data['productid'])) {
                // Thêm sản phẩm mới (INSERT)
                $stmt = $pdo->prepare(
                    "INSERT INTO Product (name, price, description, stock, cateid, mfgid, avatarurl, avgrating, bought)
                     VALUES (:name, :price, :description, :stock, :cateid, :mfgid, :avatarurl, :avgrating, :bought)"
                );

                $stmt->execute([
                    ':name' => $data['name'],
                    ':price' => (float)$data['price'],
                    ':description' => $data['description'],
                    ':stock' => (int)$data['stock'],
                    ':cateid' => $data['cateid'],
                    ':mfgid' => $data['mfgid'],
                    ':avatarurl' => $data['avatarurl'],
                    ':avgrating' => (float)$data['avgrating'],
                    ':bought' => (int)$data['bought']
                ]);

                $data['productid'] = $pdo->lastInsertId();
            } else {
                // Cập nhật sản phẩm hiện có (UPDATE)
                $stmt = $pdo->prepare(
                    "UPDATE Product 
                     SET name = :name, price = :price, description = :description, 
                         stock = :stock, cateid = :cateid, mfgid = :mfgid, 
                         avatarurl = :avatarurl, avgrating = :avgrating, bought = :bought
                     WHERE productid = :productid"
                );

                $stmt->execute([
                    ':productid' => $data['productid'],
                    ':name' => $data['name'],
                    ':price' => (float)$data['price'],
                    ':description' => $data['description'],
                    ':stock' => (int)$data['stock'],
                    ':cateid' => $data['cateid'],
                    ':mfgid' => $data['mfgid'],
                    ':avatarurl' => $data['avatarurl'],
                    ':avgrating' => (float)$data['avgrating'],
                    ':bought' => (int)$data['bought']
                ]);

                if ($stmt->rowCount() === 0) {
                    return ['success' => false, 'message' => 'Product not found or no changes made'];
                }
            }

       
            return ['success' => true];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findById($id) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("
                SELECT p.*, m.name as manufacturer_name, c.name as category_name 
                FROM Product p
                LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid
                LEFT JOIN Category c ON p.cateid = c.cateid
                WHERE p.productid = :id
            ");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            if ($result) {
                $product = ProductModel::toObject($result);
                $product->manufacturerName = $result['manufacturer_name'];
                $product->categoryName = $result['category_name'];
                return ['success' => true, 'data' => $product];
            }
            return ['success' => false, 'message' => 'Product not found'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAll($filters = []) {
        try {
            $pdo = Database::getInstance()->getConnection();

            // Base query
            $query = "SELECT p.*, m.name as manufacturer_name, c.name as category_name 
                     FROM Product p
                     LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid
                     LEFT JOIN Category c ON p.cateid = c.cateid
                     WHERE 1=1";

            $params = [];

            // Thêm chức năng search
            if (!empty($filters['search'])) {
                $query .= " AND (p.name LIKE :search_name OR p.description LIKE :search_desc)";
                $searchTerm = '%' . $filters['search'] . '%';
                $params[':search_name'] = $searchTerm;
                $params[':search_desc'] = $searchTerm;
            }

            // Apply category filter
            if (!empty($filters['category'])) {
                $query .= " AND p.cateid = :cateid";
                $params[':cateid'] = $filters['category'];
            }

            // Sắp xếp mặc định theo productid DESC (mới nhất)
            $query .= " ORDER BY p.productid DESC";

            // Prepare statement
            $stmt = $pdo->prepare($query);

            // Bind filter parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }

            $stmt->execute();

            $products = array_map(function($row) {
                $product = ProductModel::toObject($row);
                $product->manufacturerName = $row['manufacturer_name'];
                $product->categoryName = $row['category_name'];
                return $product;
            }, $stmt->fetchAll());

            return ['success' => true, 'data' => $products];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function getRelatedProducts($productId, $limit = 4) {
        try {
            $product = self::findById($productId);
            if (!$product['success']) {
                return $product;
            }

            $stmt = Database::getInstance()->getConnection()->prepare("
                SELECT p.*, m.name as manufacturer_name, c.name as category_name 
                FROM Product p
                LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid
                LEFT JOIN Category c ON p.cateid = c.cateid
                WHERE p.cateid = :cateid AND p.productid != :productid
                ORDER BY RAND()
                LIMIT :limit
            ");
            
            $stmt->bindValue(':cateid', $product['data']->getCateid(), \PDO::PARAM_INT);
            $stmt->bindValue(':productid', $productId, \PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            
            $products = array_map(function($row) {
                $product = ProductModel::toObject($row);
                $product->manufacturerName = $row['manufacturer_name'];
                $product->categoryName = $row['category_name'];
                return $product;
            }, $stmt->fetchAll());

            return ['success' => true, 'data' => $products];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findByCategory($cateid) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("
                SELECT p.*, m.name as manufacturer_name, c.name as category_name 
                FROM Product p
                LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid
                LEFT JOIN Category c ON p.cateid = c.cateid
                WHERE p.cateid = :cateid
                ORDER BY p.productid DESC
            ");
            $stmt->execute([':cateid' => $cateid]);
            $products = array_map(function($row) {
                $product = ProductModel::toObject($row);
                $product->manufacturerName = $row['manufacturer_name'];
                $product->categoryName = $row['category_name'];
                return $product;
            }, $stmt->fetchAll());

            return ['success' => true, 'data' => $products];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findByManufacturer($mfgid) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("
                SELECT p.*, m.name as manufacturer_name, c.name as category_name 
                FROM Product p
                LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid
                LEFT JOIN Category c ON p.cateid = c.cateid
                WHERE p.mfgid = :mfgid
                ORDER BY p.productid DESC
            ");
            $stmt->execute([':mfgid' => $mfgid]);
            $products = array_map(function($row) {
                $product = ProductModel::toObject($row);
                $product->manufacturerName = $row['manufacturer_name'];
                $product->categoryName = $row['category_name'];
                return $product;
            }, $stmt->fetchAll());

            return ['success' => true, 'data' => $products];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM Product WHERE productid = :id");
            $stmt->execute([':id' => $id]);

            return ['success' => $stmt->rowCount() > 0, 'message' => 'Product deleted successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function updateRating($productid, $newRating) {
        try {
            $product = self::findById($productid);
            if (!$product['success']) {
                return $product;
            }

            $productModel = $product['data'];
            $currentRating = $productModel->getAvgrating();
            $newAvg = ($currentRating + $newRating) / 2;
            $productModel->setAvgrating($newAvg);

            return self::save($productModel);
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findByProductId($productid) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare(
                "SELECT * FROM RateProduct WHERE productid = :productid ORDER BY ratingdate DESC"
            );
            $stmt->execute([':productid' => $productid]);
            $data = array_map(fn($row) => RateProductModel::toObject($row), $stmt->fetchAll());
    
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function incrementBoughtCount($productid) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare(
                "UPDATE Product SET bought = bought + 1 WHERE productid = :id"
            );
            $stmt->execute([':id' => $productid]);

            return ['success' => $stmt->rowCount() > 0, 'message' => 'Bought count incremented'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function toArray($obj) {
        return [
            'productid' => $obj->getProductid(),
            'name' => $obj->getName(),
            'price' => $obj->getPrice(),
            'description' => $obj->getDescription(),
            'avgrating' => $obj->getAvgrating(),
            'bought' => $obj->getBought(),
            'mfgid' => $obj->getMfgid(),
            'stock' => $obj->getStock(),
            'cateid' => $obj->getCateid(),
            'avatarurl' => $obj->getAvatarurl()
        ];
    }

    ///////////////////////////// ADDED BY LINH /////////////////////////////
    public static function getTmp(int $limit): array
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("
                SELECT p.*, m.name as manufacturer
                FROM Product p
                LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid
                ORDER BY bought DESC
                LIMIT :limit
            ");
            $stmt->execute([':limit' => $limit]);
            $products = array_map(function($row) {
                return ProductModel::toObject($row);
            }, $stmt->fetchAll());

            return ['success' => true, 'data' => $products];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function getNewestProducts(int $limit): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM product ORDER BY avgrating DESC LIMIT :limit");
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(\PDO::FETCH_CLASS, ProductModel::class);

            return ['success' => true, 'data' => $products];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function getTopRatedProducts(int $limit): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM product ORDER BY avgrating DESC LIMIT :limit");
//            $stmt = $pdo->prepare("SELECT p.*, m.name AS brandName FROM product p LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid ORDER BY p.created_at DESC LIMIT :limit");
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(\PDO::FETCH_CLASS, ProductModel::class);

            return ['success' => true, 'data' => $products];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function getBestSellers(int $limit): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM product ORDER BY bought DESC LIMIT :limit");
//            $stmt = $pdo->prepare("SELECT p.*, m.name AS brandName FROM product p LEFT JOIN Manufacturer m ON p.mfgid = m.mfgid ORDER BY p.bought DESC LIMIT :limit");
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(\PDO::FETCH_CLASS, ProductModel::class);

            return ['success' => true, 'data' => $products];
        } catch (Exception $e) {
            return handleException($e);
        }
    }
    ///////////////////////////// ADDED BY LINH /////////////////////////////
}