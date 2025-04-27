<?php
namespace service;

use core\Database;
use core\FileCategory;
use core\FileManager;
use core\IService;
use core\Logger;
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

    public static function updateAvatar($avatar, $customerid)
    {
        Logger::log("Updating avatar for customer ID: $customerid");

        if (!isset($avatar) || !isset($customerid)) {
            Logger::log("Invalid parameters for updating avatar");
            return  ['success' => false, 'message' => 'Invalid parameters'];
        }

        try {
            $result = FileManager::getInstance()->Save($avatar, $customerid, FileCategory::AVATAR, true);
            if (!$result['success']) {
                Logger::log("Failed to save avatar: " . $result['message']);
                return ['success' => false, 'message' => $result['message']];
            }
            Logger::log("File saved successfully: " . $result['data']);
            $avatarurl = $result['data'];

            $stmt = Database::getInstance()->getConnection()->prepare("UPDATE customer SET avatarurl = :avatarurl WHERE userid = :customerid");
            $params = [
                ':avatarurl' => $avatarurl,
                ':customerid' => $customerid
            ];

            $stmt->execute($params);

            Logger::log("Avatar updated successfully for customer ID: $customerid");
            return ['success' => true, 'message' => 'Avatar updated successfully'];
        } catch (Exception $e) {
            Logger::log("Error updating avatar: " . $e->getMessage());
            return handleException($e);
        }
    }
}