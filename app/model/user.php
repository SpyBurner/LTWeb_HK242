<?php
require_once __DIR__ . "/../../core/Model.php";

class User extends Model {
    private $userid;
    private $name;
    private $email;
    private $password;
    private $joindate;
    private $isAdmin;

    public function __construct($id, $name, $email, $password, $joindate = null, $isAdmin = false) {
        $this->userid = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->joindate = $joindate;
        $this->isAdmin = $isAdmin;
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

    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    public function save() {
        // Implement database save logic here
    }

    public function delete() {
    }

    public static function findById($id) {
        // Implement find by ID logic here
    }

    public static function findAll() {
        // Implement find all users logic here
    }
}
?>