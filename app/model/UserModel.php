<?php
require_once __DIR__ . "/../../core/Model.php";

class User implements IModel{
    private $userid;
    private $username;
    private $email;
    private $password;
    private $joindate;
    private $isadmin;

    public function __construct($id, $username, $email, $password, $joindate = null, $isadmin = false) {
        $this->userid = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->joindate = $joindate;
        $this->isadmin = $isadmin;
    }

    public function getUserid() {
        return $this->userid;
    }

    public function getUsername() {
        return $this->username;
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
    public function setUsername($username) {
        $this->username = $username;
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
        return "User ID: $this->userid, Name: $this->username, Email: $this->email, Password: $this->password, Join Date: $this->joindate, Is Admin: $this->isadmin";
    }

    public function save() {
        $pdo = Database::getInstance()->getConnection();
        if ($this->userid) {
            // Update existing user
            $stmt = $pdo->prepare("UPDATE user SET username = :name, email = :email, password = :password, joindate = :joindate, isadmin = :isadmin WHERE userid = :userid");
            $stmt->execute([
                ':username' => $this->username,
                ':email' => $this->email,
                ':password' => $this->password,
                ':joindate' => $this->joindate,
                ':isadmin' => $this->isadmin,
                ':userid' => $this->userid
            ]);

        } else {
            // Insert new user
            if (!$this->joindate)
                $this->joindate = date('Y-m-d H:i:s');

            $stmt = $pdo->prepare("INSERT INTO user (username, email, password, joindate, isadmin) VALUES (:username, :email, :password, :joindate, :isadmin)");
            $stmt->execute([
                ':username' => $this->username,
                ':email' => $this->email,
                ':password' => $this->password,
                ':joindate' => $this->joindate,
                ':isadmin' => $this->isadmin
            ]);
        }

        return $stmt->rowCount();
    }

    public function delete() {
        $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM user WHERE userid = :id");
        $stmt->execute(['id' => $this->userid]);
    }

    public static function deleteById($id)
    {
        $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM user WHERE userid = :id");
        $stmt->execute(['id' => $id]);
    }

    public static function findById($id)
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE userid = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        if ($result) {
            return User::toObject($result);
        } else {
            return null;
        }
    }

    public static function findAll()
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM user");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $users = [];
        foreach ($result as $row) {
            $users[] = User::toObject($row);
        }
        return $users;
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