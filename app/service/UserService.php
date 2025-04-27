<?php
namespace service;

use core\Database;
use core\IService;
use core\Logger;
use Exception;
use model\CustomerModel;
use model\UserModel;
use function core\handleException;
use const config\DEFAULT_MOD_AVATAR_URL;

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

                $stmt->execute($params);
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
                $stmt->execute($params);

                //Also create a customer record
                $id = $pdo->lastInsertId();

                $success = false;
                $find_result = self::findById($id);
                if ($find_result['success']) {
                    Logger::log("UserService", "User with ID $id created/updated successfully");

                    $customerResult = CustomerService::save(new CustomerModel($id));

                    if ($customerResult['success']) {
                        $extra_msg = $customerResult['message'];
                        $success = true;
                    }
                }

                if (!$success) {
                    self::deleteById($id);
                    return ['success' => false, 'message' => 'User creation successful, but customer creation failed. User deleted.'];
                }
            }

            return ['success' => true, 'message' => ($model->getUserid() ? 'User updated successfully' : 'User created successfully') . ', ' . $extra_msg] ;
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

            if ($result){
                $model = UserModel::toObject($result);

                if (!$model->getisadmin())
                    $avatar =  CustomerService::findById($id)['data']->getAvatarurl();
                else $avatar = DEFAULT_MOD_AVATAR_URL;

                $ret = ['success' => true, 'data' => $model, 'avatar' => $avatar];
            }
            else
                $ret = ['success' => false, 'message' => 'User not found'];
            return $ret;
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

            if ($result){
                $model = UserModel::toObject($result);

                if (!$model->getisadmin())
                    $avatar =  CustomerService::findById($model->getUserid())['data']->getAvatarurl();
                else $avatar = DEFAULT_MOD_AVATAR_URL;

                $ret = ['success' => true, 'data' => $model, 'avatar' => $avatar];
            }
            else
                $ret = ['success' => false, 'message' => 'No user found with this email'];

            return $ret;
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
