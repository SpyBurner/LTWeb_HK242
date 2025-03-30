<?php
namespace service;

use core\Database;
use core\IService;
use Exception;
use model\ContactModel;
use function core\handleException;

class ContactService implements IService
{
    public static function save($model)
    {
        if (!$model instanceof ContactModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        try {
            $pdo = Database::getInstance()->getConnection();

            if ($model->getContactId()) {
                // Update existing contact
                $stmt = $pdo->prepare("
                    UPDATE Contact 
                    SET name = :name, phone = :phone, address = :address, customerid = :customerid 
                    WHERE contactid = :contactid
                ");
                $params = [
                    ':name' => $model->getName(),
                    ':phone' => $model->getPhone(),
                    ':address' => $model->getAddress(),
                    ':customerid' => $model->getCustomerId(),
                    ':contactid' => $model->getContactId() // Only used for updates
                ];
            } else {
                // Insert new contact
                $stmt = $pdo->prepare("
                    INSERT INTO Contact (name, phone, address, customerid) 
                    VALUES (:name, :phone, :address, :customerid)
                ");
                $params = [
                    ':name' => $model->getName(),
                    ':phone' => $model->getPhone(),
                    ':address' => $model->getAddress(),
                    ':customerid' => $model->getCustomerId()
                ];
            }

            $stmt->execute($params);

            return ['success' => true, 'message' => $model->getContactId() ? 'Contact updated successfully' : 'Contact created successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Contact WHERE contactid = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            return $result ? ['success' => true, 'data' => ContactModel::toObject($result)] : ['success' => false, 'message' => 'Contact not found'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAll()
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Contact");
            $stmt->execute();
            $contacts = array_map(fn($row) => ContactModel::toObject($row), $stmt->fetchAll());

            return ['success' => true, 'data' => $contacts];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAllByCustomerId($customerId)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Contact WHERE customerid = :customerid");
            $stmt->execute([':customerid' => $customerId]);

            $contacts = array_map(fn($row) => ContactModel::toObject($row), $stmt->fetchAll());

            return ['success' => true, 'data' => $contacts];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM Contact WHERE contactid = :id");
            $stmt->execute([':id' => $id]);

            return ['success' => $stmt->rowCount() > 0, 'message' => 'Contact deleted successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }
}
