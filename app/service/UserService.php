<?php
namespace service;

use core\Database;
use core\IService;
use Exception;
use model\UserModel;
use function core\handleException;
class UserService implements IService
{
    public static function save($model)
    {
        if (!$model instanceof UserModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        try {
            $pdo = Database::getInstance()->getConnection();

            if ($model->getUserid()) {
                // Update existing user
                $stmt = $pdo->prepare("
                    UPDATE user 
                    SET username = :username, email = :email, password = :password, joindate = :joindate, isadmin = :isadmin 
                    WHERE userid = :userid
                ");
                $params = [
                    ':username' => $model->getUsername(),
                    ':email' => $model->getEmail(),
                    ':password' => $model->getPassword(),
                    ':joindate' => $model->getJoindate(),
                    ':isadmin' => $model->getisadmin(),
                    ':userid' => $model->getUserid() // Only used for updates
                ];
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO user (username, email, password, joindate, isadmin) 
                    VALUES (:username, :email, :password, :joindate, :isadmin)
                ");
                $params = [
                    ':username' => $model->getUsername(),
                    ':email' => $model->getEmail(),
                    ':password' => $model->getPassword(),
                    ':joindate' => $model->getJoindate(),
                    ':isadmin' => $model->getisadmin()
                ];
            }

            $stmt->execute($params);

            return ['success' => true, 'message' => $model->getUserid() ? 'User updated successfully' : 'User created successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE userid = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            return $result ? ['success' => true, 'data' => UserModel::toObject($result)] : ['success' => false, 'message' => 'User not found'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }


    public static function findByEmail($email)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $result = $stmt->fetch();

            return $result ? ['success' => true, 'data' => UserModel::toObject($result)] : ['success' => false, 'message' => 'Email not found'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAll()
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM user");
            $stmt->execute();
            $users = array_map(fn($row) => UserModel::toObject($row), $stmt->fetchAll());

            return ['success' => true, 'data' => $users];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM user WHERE userid = :id");
            $stmt->execute([':id' => $id]);

            return ['success' => $stmt->rowCount() > 0, 'message' => 'User deleted successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }


}
