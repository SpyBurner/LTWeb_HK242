<?php
namespace service;

use core\Database;
use core\IService;
use model\ProductModel;
use Exception;
use function core\handleException;

class HomeService implements IService {
    public static function searchProducts($keyword, $category, $sortBy, $page, $limit) {
        try {
            $db = Database::getInstance();
            $query = "SELECT * FROM products WHERE name LIKE ? AND cateid = ? ORDER BY ? LIMIT ?, ?";
            $stmt = $db->prepare($query);
            $stmt->bindValue(1, "%$keyword%");
            $stmt->bindValue(2, $category);
            $stmt->bindValue(3, $sortBy);
            $stmt->bindValue(4, ($page - 1) * $limit, \PDO::PARAM_INT);
            $stmt->bindValue(5, (int)$limit, \PDO::PARAM_INT);
            $stmt->execute();
            
            return ['success' => true, 'data' => $stmt->fetchAll(\PDO::FETCH_CLASS, ProductModel::class)];
        } catch (Exception $e) {
            handleException($e);
            return ['success' => false, 'message' => 'Error fetching products'];
        }
    }

  
}