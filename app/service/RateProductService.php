<?php
namespace service;

use core\Database;
use core\IService;
use Exception;
use model\RateProductModel;
use function core\handleException;

class RateProductService implements IService
{
    public static function findAll()
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM RateProduct");
            $stmt->execute();

            $data = array_map(fn($row) => RateProductModel::toObject($row), $stmt->fetchAll());
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findById($productid)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare(
                "SELECT rp.*, c.avatarurl, u.username
                 FROM RateProduct rp
                 JOIN `Order` o ON rp.orderid = o.orderid
                 JOIN Customer c ON o.customerid = c.userid
                 JOIN User u ON c.userid = u.userid
                 WHERE rp.productid = :productid"
            );
            $stmt->execute(['productid' => $productid]);

            $rows = $stmt->fetchAll();
            if (!$rows) {
                return ['success' => false, 'message' => 'No ratings found for this product'];
            }

            $data = array_map(fn($row) => RateProductModel::toObject($row), $rows);
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findByOrderId($orderid)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare(
                "SELECT * FROM RateProduct WHERE orderid = :orderid"
            );
            $stmt->execute(['orderid' => $orderid]);

            $rows = $stmt->fetchAll();
            if (!$rows) {
                return ['success' => false, 'message' => 'No ratings found for this order'];
            }

            $data = array_map(fn($row) => RateProductModel::toObject($row), $rows);
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function save($model)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare(
                "INSERT INTO RateProduct (orderid, productid, rating, comment, ratingdate)
                 VALUES (:orderid, :productid, :rating, :comment, :ratingdate)"
            );

            $stmt->execute([
                'orderid' => $model->getOrderid(),
                'productid' => $model->getProductid(),
                'rating' => $model->getRating(),
                'comment' => $model->getComment(),
                'ratingdate' => $model->getRatingDate()
            ]);

            return ['success' => true];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function update($model)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare(
                "UPDATE RateProduct
                 SET rating = :rating, comment = :comment, ratingdate = :ratingdate
                 WHERE orderid = :orderid AND productid = :productid"
            );

            $stmt->execute([
                'rating' => $model->getRating(),
                'comment' => $model->getComment(),
                'ratingdate' => $model->getRatingDate(),
                'orderid' => $model->getOrderid(),
                'productid' => $model->getProductid()
            ]);

            return ['success' => true];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id)
    {
        try {
            [$orderid, $productid] = $id;

            $stmt = Database::getInstance()->getConnection()->prepare(
                "DELETE FROM RateProduct WHERE orderid = :orderid AND productid = :productid"
            );
            $stmt->execute(['orderid' => $orderid, 'productid' => $productid]);

            return ['success' => true];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

}
