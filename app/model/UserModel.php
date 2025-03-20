<?php
require_once __DIR__ . "/../../core/Model.php";

class User implements IModel{
    private $userid;
    private $name;
    private $email;
    private $password;
    private $joindate;
    private $isadmin;

    public function __construct($id, $name, $email, $password, $joindate = null, $isadmin = false) {
        $this->userid = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->joindate = $joindate;
        $this->isadmin = $isadmin;
    }

    public function getUserid() {
        return $this->userid;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getJoindate() {
        return $this->joindate;
    }

    public function getIsadmin() {
        return $this->isadmin;
    }

// Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setJoindate($joindate) {
        $this->joindate = $joindate;
    }

    public function setIsadmin($isadmin) {
        $this->isadmin = $isadmin;
    }

    public function __toString() {
        return "User ID: $this->userid, Name: $this->name, Email: $this->email, Password: $this->password, Join Date: $this->joindate, Is Admin: $this->isadmin";
    }

    public function save() {
        $pdo = Database::getInstance()->getConnection();

        try {
            if ($this->userid) {
                // Update existing user
                $stmt = $pdo->prepare("UPDATE user SET name = :name, email = :email, password = :password, joindate = :joindate, isadmin = :isadmin WHERE userid = :userid");
                $stmt->execute([
                    ':name' => $this->name,
                    ':email' => $this->email,
                    ':password' => $this->password,
                    ':joindate' => $this->joindate,
                    ':isadmin' => $this->isadmin,
                    ':userid' => $this->userid
                ]);

            } else {
                // Insert new user
                $stmt = $pdo->prepare("INSERT INTO user (name, email, password, joindate, isadmin) VALUES (:name, :email, :password, :joindate, :isadmin)");
                $stmt->execute([
                    ':name' => $this->name,
                    ':email' => $this->email,
                    ':password' => $this->password,
                    ':joindate' => $this->joindate,
                    ':isadmin' => $this->isadmin
                ]);
            }

            return $stmt->rowCount();
        } catch (Exception $e) {
            echo new Exception("Error saving user: " . $e->getMessage());
        }
        return null;
    }

    public function delete() {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM user WHERE userid = :id");
            $stmt->execute(['id' => $this->userid]);
        } catch (Exception $e) {
            echo new Exception("Error deleting user: " . $e->getMessage());
        }
    }

    public static function deleteById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM user WHERE userid = :id");
            $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            echo new Exception("Error deleting user: " . $e->getMessage());
        }
    }

    public static function findById($id) {

        try {// Implement find by ID logic here
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE userid = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();
            if ($result) {
                return User::toObject($result);
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo new Exception("Error finding user: " . $e->getMessage());
        }
        return null;
    }

    public static function findAll() {

        try {// Implement find all logic here
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM user");
            $stmt->execute();
            $result = $stmt->fetchAll();
            $users = [];
            foreach ($result as $row) {
                $users[] = User::toObject($row);
            }
            return $users;
        } catch (Exception $e) {
            echo new Exception("Error finding user: " . $e->getMessage());
        }
        return null;
    }

    public static function toObject($row){
        return new User($row['userid'], $row['username'], $row['email'], $row['password'], $row['joindate'], $row['isadmin']);
    }

    public static function toArray($obj){
        return [
            'userid' => $obj->getUserid(),
            'name' => $obj->getName(),
            'email' => $obj->getEmail(),
            'password' => $obj->getPassword(),
            'joindate' => $obj->getJoindate(),
            'isAdmin' => $obj->getIsAdmin()
        ];
    }
}