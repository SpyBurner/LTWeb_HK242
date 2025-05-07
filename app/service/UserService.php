<?php
namespace service;

use core\Database;
use core\FileCategory;
use core\FileManager;
use core\IService;
use core\Logger;
use Exception;
use model\CustomerModel;
use model\UserModel;
use function core\handleException;
use const config\DEFAULT_AVATAR_URL;
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
                        $success = true;
                    }
                    else {
                        Logger::log("UserService", "Failed to create customer for user ID $id: " . $customerResult['message']);
                    }
                    $extra_msg = $customerResult['message'];
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

                if (!$model->getisadmin()){
                    $result = CustomerService::findById($id);
                    if ($result['success']){
                        $avatar = $result['data']->getAvatarurl();
                    }
                    else {
                        Logger::log("UserService " . "Failed to find customer for user ID: $id");
                        $avatar = DEFAULT_AVATAR_URL;
                    }
                }
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

                if (!$model->getisadmin()){
                    $result = CustomerService::findById($model->getUserid());
                    if ($result['success']){
                        $avatar = $result['data']->getAvatarurl();
                    }
                    else {
                        Logger::log("UserService: " . "Failed to find customer for user ID: " . $model->getUserid());
                        $avatar = DEFAULT_AVATAR_URL;
                    }
                }
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

    public static function findByToken($token)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE token = :token");
            $stmt->execute([':token' => $token]);
            $result = $stmt->fetch();

            if ($result){
                $model = UserModel::toObject($result);

                if ($model->getTokenExpiration() < time()) {
                    return ['success' => false, 'message' => 'Token expired'];
                }

                $ret = ['success' => true, 'data' => $model];
            }
            else
                $ret = ['success' => false, 'message' => 'No user found with this token'];

            return $ret;
        }
        catch (Exception $e) {
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

            // Also delete the avatar in case this is a customer
            $result = FileManager::getInstance()->Delete($id, FileCategory::AVATAR);
            if (!$result['success']) {
                Logger::log("Failed to delete avatar: " . $result['message'] . " for user ID: $id");
            }

            if ($stmt->rowCount() == 0) {
                Logger::log("No user found with ID: $id");
                return ['success' => false, 'message' => 'User not found'];
            }
            else {
                Logger::log("User with ID: $id deleted successfully");
                return ['success' => true, 'message' => 'User deleted successfully'];
            }
        } catch (Exception $e) {
            Logger::log("Failed to delete user: " . $e->getMessage());
            return handleException($e);
        }
    }


}
