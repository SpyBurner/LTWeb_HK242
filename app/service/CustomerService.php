<?php
namespace service;

use core\Database;
use core\IService;
use Exception;
use model\CustomerModel;
use function core\handleException;

class CustomerService implements IService
{

    public static function save($model)
    {
        if (!$model instanceof CustomerModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        try {
            $pdo = Database::getInstance()->getConnection();

            $stmt = $pdo->prepare("
                INSERT INTO customer 
                VALUES (:userid, :avatarurl)
            ");
            $params = [
                ':userid' => $model->getUserid(),
                ':avatarurl' => $model->getAvatarurl()
            ];

            $stmt->execute($params);

            return ['success' => true, 'message' => $model->getUserid() ? 'Customer updated successfully' : 'Customer created successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM customer WHERE userid = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            return $result ? ['success' => true, 'data' => CustomerModel::toObject($result)] : ['success' => false, 'message' => 'User not found'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }
    public static function findAll()
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM customer");
            $stmt->execute();
            $users = array_map(fn($row) => CustomerModel::toObject($row), $stmt->fetchAll());

            return ['success' => true, 'data' => $users];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id)
    {
        return ['success' => false, 'message' => 'Delete operation not supported for CustomerService. Please delete from UserService'];
    }
}