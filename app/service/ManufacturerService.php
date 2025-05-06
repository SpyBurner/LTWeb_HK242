<?php
namespace service;

use core\Database;
use core\IService;
use Exception;
use model\ManufacturerModel;
use function core\handleException;

class ManufacturerService implements IService {
    public static function findAll() {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Manufacturer");
            $stmt->execute();
            $manufacturers = array_map(fn($row) => ManufacturerModel::toObject($row), $stmt->fetchAll());

            return ['success' => true, 'data' => $manufacturers];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findById($id) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Manufacturer WHERE mfgid = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            return $result ? ['success' => true, 'data' => ManufacturerModel::toObject($result)] 
                          : ['success' => false, 'message' => 'Manufacturer not found'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function save($model) {
        if (!$model instanceof ManufacturerModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        try {
            $pdo = Database::getInstance()->getConnection();

            if ($model->getMfgid()) {
                // Update existing manufacturer
                $stmt = $pdo->prepare("UPDATE Manufacturer SET name = :name, country = :country WHERE mfgid = :mfgid");
                $params = [
                    ':name' => $model->getName(),
                    ':country' => $model->getCountry(),
                    ':mfgid' => $model->getMfgid()
                ];
            } else {
                // Insert new manufacturer
                $stmt = $pdo->prepare("INSERT INTO Manufacturer (name, country) VALUES (:name, :country)");
                $params = [
                    ':name' => $model->getName(),
                    ':country' => $model->getCountry()
                ];
            }

            $stmt->execute($params);

            if (!$model->getMfgid()) {
                $model->setMfgid($pdo->lastInsertId());
            }

            return ['success' => true, 'data' => $model];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM Manufacturer WHERE mfgid = :id");
            $stmt->execute([':id' => $id]);

            return ['success' => $stmt->rowCount() > 0, 'message' => 'Manufacturer deleted successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }
}