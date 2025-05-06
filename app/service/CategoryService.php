<?php
namespace service;

use core\Database;
use core\IService;
use Exception;
use model\CategoryModel;
use function core\handleException;

class CategoryService implements IService {
    public static function findAll() {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Category");
            $stmt->execute();
            $categories = array_map(fn($row) => CategoryModel::toObject($row), $stmt->fetchAll());

            return ['success' => true, 'data' => $categories];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findById($id) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Category WHERE cateid = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            return $result ? ['success' => true, 'data' => CategoryModel::toObject($result)] 
                          : ['success' => false, 'message' => 'Category not found'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function save($model) {
        if (!$model instanceof CategoryModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        try {
            $pdo = Database::getInstance()->getConnection();

            if ($model->getCateid()) {
                // Update existing category
                $stmt = $pdo->prepare("UPDATE Category SET name = :name WHERE cateid = :cateid");
                $params = [
                    ':name' => $model->getName(),
                    ':cateid' => $model->getCateid()
                ];
            } else {
                // Insert new category
                $stmt = $pdo->prepare("INSERT INTO Category (name) VALUES (:name)");
                $params = [
                    ':name' => $model->getName()
                ];
            }

            $stmt->execute($params);

            if (!$model->getCateid()) {
                $model->setCateid($pdo->lastInsertId());
            }

            return ['success' => true, 'data' => $model];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM Category WHERE cateid = :id");
            $stmt->execute([':id' => $id]);

            return ['success' => $stmt->rowCount() > 0, 'message' => 'Category deleted successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }
}