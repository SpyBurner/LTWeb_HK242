<?php
namespace service;

use core\Database;
use core\IService;
use Exception;
use model\OrderModel;
use function core\handleException;

class OrderService implements IService {
    public static function findAllWithDetails($filters = []) {
        try {
            $query = "SELECT o.*, u.username as customer_name 
                     FROM `Order` o
                     JOIN User u ON o.customerid = u.userid
                     WHERE o.status != 'Cart'";
            
            $params = [];
            
            if (!empty($filters['search'])) {
                $query .= " AND (u.username LIKE :search OR o.orderid LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            if (!empty($filters['status'])) {
                $query .= " AND o.status = :status";
                $params[':status'] = $filters['status'];
            }
            
            $query .= " ORDER BY o.orderid DESC";
            
            $stmt = Database::getInstance()->getConnection()->prepare($query);
            $stmt->execute($params);
            $orders = $stmt->fetchAll();

            $ordersWithDetails = [];
            foreach ($orders as $order) {
                $orderModel = OrderModel::toObject($order);
                $orderModel->customerName = $order['customer_name'];
                
                $productsStmt = Database::getInstance()->getConnection()->prepare(
                    "SELECT p.productid, p.name, p.price, p.description, p.avgrating, 
                            p.bought, p.stock, p.avatarurl, hp.amount,
                            c.name as category_name, m.name as manufacturer_name
                     FROM HasProduct hp
                     JOIN Product p ON hp.productid = p.productid
                     JOIN Category c ON p.cateid = c.cateid
                     JOIN Manufacturer m ON p.mfgid = m.mfgid
                     WHERE hp.orderid = :orderid"
                );
                $productsStmt->execute([':orderid' => $order['orderid']]);
                $orderModel->products = $productsStmt->fetchAll();
                
                $ordersWithDetails[] = $orderModel;
            }
            
            return ['success' => true, 'data' => $ordersWithDetails];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function updateStatus($orderId, $newStatus) {
        try {
            $validStatuses = ['Preparing', 'Prepared', 'Delivering', 'Delivered'];
            if (!in_array($newStatus, $validStatuses)) {
                return ['success' => false, 'message' => 'Invalid status'];
            }

            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare(
                "UPDATE `Order` SET status = :status WHERE orderid = :orderid"
            );
            
            $stmt->execute([
                ':status' => $newStatus,
                ':orderid' => $orderId
            ]);

            if ($stmt->rowCount() === 0) {
                return ['success' => false, 'message' => 'Order not found or no changes made'];
            }

            return ['success' => true, 'message' => 'Order status updated'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function addProductToOrder($orderId, $productId, $amount) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare(
                "INSERT INTO HasProduct (orderid, productid, amount) 
                 VALUES (:orderid, :productid, :amount)
                 ON DUPLICATE KEY UPDATE amount = amount + VALUES(amount)"
            );
            
            $stmt->execute([
                ':orderid' => $orderId,
                ':productid' => $productId,
                ':amount' => $amount
            ]);

            self::updateOrderTotal($orderId);

            return ['success' => true, 'message' => 'Product added to order'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function removeProductFromOrder($orderId, $productId) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare(
                "DELETE FROM HasProduct 
                 WHERE orderid = :orderid AND productid = :productid"
            );
            
            $stmt->execute([
                ':orderid' => $orderId,
                ':productid' => $productId
            ]);

            self::updateOrderTotal($orderId);

            return ['success' => $stmt->rowCount() > 0, 'message' => 'Product removed from order'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    private static function updateOrderTotal($orderId) {
        try {
            $pdo = Database::getInstance()->getConnection();
            
            $stmt = $pdo->prepare(
                "SELECT SUM(p.price * hp.amount) as new_total
                 FROM HasProduct hp
                 JOIN Product p ON hp.productid = p.productid
                 WHERE hp.orderid = :orderid"
            );
            $stmt->execute([':orderid' => $orderId]);
            $result = $stmt->fetch();
            
            $newTotal = $result['new_total'] ?? 0;
            
            $updateStmt = $pdo->prepare(
                "UPDATE `Order` SET totalcost = :total WHERE orderid = :orderid"
            );
            $updateStmt->execute([
                ':total' => $newTotal,
                ':orderid' => $orderId
            ]);
            
            return ['success' => true, 'newTotal' => $newTotal];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function getCartByUserId($userId) {
        try {
            $pdo = Database::getInstance()->getConnection();
            
            $stmt = $pdo->prepare(
                "SELECT o.*, u.username as customer_name 
                 FROM `Order` o
                 JOIN User u ON o.customerid = u.userid
                 WHERE o.customerid = :customerid AND o.status = 'Cart'"
            );
            $stmt->execute([':customerid' => $userId]);
            $order = $stmt->fetch();

            if (!$order) {
                return ['success' => true, 'data' => null, 'message' => 'No cart found for this user'];
            }

            $orderModel = OrderModel::toObject($order);
            $orderModel->customerName = $order['customer_name'];

            $productsStmt = $pdo->prepare(
                "SELECT p.productid, p.name, p.price, p.description, p.avgrating, 
                        p.bought, p.stock, p.avatarurl, hp.amount,
                        c.name as category_name, m.name as manufacturer_name
                 FROM HasProduct hp
                 JOIN Product p ON hp.productid = p.productid
                 JOIN Category c ON p.cateid = c.cateid
                 JOIN Manufacturer m ON p.mfgid = m.mfgid
                 WHERE hp.orderid = :orderid"
            );
            $productsStmt->execute([':orderid' => $order['orderid']]);
            $orderModel->products = $productsStmt->fetchAll();

            return ['success' => true, 'data' => $orderModel];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function addToCart($userId, $productId, $amount, $contactId = null) {
        try {
            $pdo = Database::getInstance()->getConnection();
            
            $cartStmt = $pdo->prepare(
                "SELECT orderid FROM `Order` 
                 WHERE customerid = :customerid AND status = 'Cart'"
            );
            $cartStmt->execute([':customerid' => $userId]);
            $cart = $cartStmt->fetch();

            if ($cart) {
                $orderId = $cart['orderid'];
            } else {
                $insertStmt = $pdo->prepare(
                    "INSERT INTO `Order` (status, totalcost, customerid, contactid) 
                     VALUES ('Cart', 0, :customerid, :contactid)"
                );
                $insertStmt->execute([
                    ':customerid' => $userId,
                    ':contactid' => $contactId
                ]);
                $orderId = $pdo->lastInsertId();
            }

            return self::addProductToOrder($orderId, $productId, $amount);
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function reduceProductInOrder($orderId, $productId, $amount) {
        try {
            $pdo = Database::getInstance()->getConnection();

            $checkStmt = $pdo->prepare(
                "SELECT amount FROM HasProduct WHERE orderid = :orderid AND productid = :productid"
            );
            $checkStmt->execute([':orderid' => $orderid, ':productid' => $productId]);
            $currentAmount = $checkStmt->fetchColumn();

            if ($currentAmount === false) {
                return ['success' => false, 'message' => 'Product not found in cart'];
            }

            $newAmount = $currentAmount - $amount;

            if ($newAmount <= 0) {
                $deleteStmt = $pdo->prepare(
                    "DELETE FROM HasProduct WHERE orderid = :orderid AND productid = :productid"
                );
                $deleteStmt->execute([':orderid' => $orderId, ':productid' => $productId]);
            } else {
                $updateStmt = $pdo->prepare(
                    "UPDATE HasProduct SET amount = :new_amount 
                     WHERE orderid = :orderid AND productid = :productid"
                );
                $updateStmt->execute([
                    ':new_amount' => $newAmount,
                    ':orderid' => $orderId,
                    ':productid' => $productId
                ]);
            }

            self::updateOrderTotal($orderId);

            return ['success' => true, 'message' => 'Product quantity reduced'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function getOrderById($orderId) {
        try {
            $pdo = Database::getInstance()->getConnection();

            $stmt = $pdo->prepare(
                "SELECT o.*, u.username as customer_name 
                 FROM `Order` o
                 JOIN User u ON o.customerid = u.userid
                 WHERE o.orderid = :orderid"
            );
            $stmt->execute([':orderid' => $orderId]);
            $order = $stmt->fetch();

            if (!$order) {
                return ['success' => false, 'message' => 'Order not found'];
            }

            $orderModel = OrderModel::toObject($order);
            $orderModel->customerName = $order['customer_name'];

            $productsStmt = $pdo->prepare(
                "SELECT p.productid, p.name, p.price, p.description, p.avgrating, 
                        p.bought, p.stock, p.avatarurl, hp.amount,
                        c.name as category_name, m.name as manufacturer_name
                 FROM HasProduct hp
                 JOIN Product p ON hp.productid = p.productid
                 JOIN Category c ON p.cateid = c.cateid
                 JOIN Manufacturer m ON p.mfgid = m.mfgid
                 WHERE hp.orderid = :orderid"
            );
            $productsStmt->execute([':orderid' => $orderId]);
            $orderModel->products = $productsStmt->fetchAll();

            return ['success' => true, 'data' => $orderModel];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function updateProductStockAndBought($orderId) {
        try {
            $pdo = Database::getInstance()->getConnection();

            // Get all products and their amounts for the given order
            $productsStmt = $pdo->prepare(
                "SELECT hp.productid, hp.amount
                 FROM HasProduct hp
                 WHERE hp.orderid = :orderid"
            );
            $productsStmt->execute([':orderid' => $orderId]);
            $products = $productsStmt->fetchAll();

            if (empty($products)) {
                return ['success' => false, 'message' => 'No products found in order'];
            }

            // Start transaction to ensure atomic updates
            $pdo->beginTransaction();

            foreach ($products as $product) {
                // Check current stock
                $stockStmt = $pdo->prepare(
                    "SELECT stock FROM Product WHERE productid = :productid FOR UPDATE"
                );
                $stockStmt->execute([':productid' => $product['productid']]);
                $currentStock = $stockStmt->fetchColumn();

                if ($currentStock === false) {
                    $pdo->rollBack();
                    return ['success' => false, 'message' => 'Product not found: ' . $product['productid']];
                }

                if ($currentStock < $product['amount']) {
                    $pdo->rollBack();
                    return ['success' => false, 'message' => 'Insufficient stock for product: ' . $product['productid']];
                }

                // Update stock and bought
                $updateStmt = $pdo->prepare(
                    "UPDATE Product 
                     SET stock = stock - :amount, 
                         bought = bought + :amount2 
                     WHERE productid = :productid"
                );
                $updateStmt->execute([
                    ':amount' => $product['amount'],
                    ':amount2' => $product['amount'],
                    ':productid' => $product['productid']
                ]);

                if ($updateStmt->rowCount() === 0) {
                    $pdo->rollBack();
                    return ['success' => false, 'message' => 'Failed to update product: ' . $product['productid']];
                }
            }

            $pdo->commit();
            return ['success' => true, 'message' => 'Product stock and bought counts updated successfully'];
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return handleException($e);
        }
    }

    public static function updateContactId($orderId, $contactId) {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare(
                "UPDATE `Order` SET contactid = :contactid WHERE orderid = :orderid"
            );
            
            $stmt->execute([
                ':contactid' => $contactId,
                ':orderid' => $orderId
            ]);

            if ($stmt->rowCount() === 0) {
                return ['success' => false, 'message' => 'Order not found or no changes made'];
            }

            return ['success' => true, 'message' => 'Order contact updated successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function save($model) {}
    public static function findById($id) {}
    public static function findAll() {}
    public static function deleteById($id) {}
}