<?php
namespace model;

use core\IModel;

class UserModel implements IModel{
    private $userid;
    private $username;
    private $email;
    private $password;
    private $joindate;
    private $isadmin;
    private $token;
    private $token_expiration;

    /**
     * @return mixed
     */
    public function getTokenExpiration()
    {
        return $this->token_expiration;
    }

    /**
     * @param mixed $token_expiration
     */
    public function setTokenExpiration($token_expiration): void
    {
        $this->token_expiration = $token_expiration;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function __construct($id, $username, $email, $password, $joindate = null, $isadmin = false, $token = null, $token_expiration = null) {
        $this->userid = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->joindate = $joindate ?? date('Y-m-d H:i:s');
        $this->isadmin = $isadmin;
        $this->token = $token;
        $this->token_expiration = $token_expiration;
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
    public function setUserid($userid) {
        $this->userid = $userid;
    }

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
    public static function toObject($row){
        return new UserModel(
            $row['userid'],
            $row['username'],
            $row['email'],
            $row['password'],
            $row['joindate'],
            $row['isadmin'],
            $row['token'],
            $row['token_expiration']
        );
    }

    public static function toArray($obj){
        return [
            'userid' => $obj->getUserid(),
            'name' => $obj->getName(),
            'email' => $obj->getEmail(),
            'password' => $obj->getPassword(),
            'joindate' => $obj->getJoindate(),
            'isAdmin' => $obj->getIsAdmin(),
            'token' => $obj->getToken(),
            'token_expiration' => $obj->getTokenExpiration()
        ];
    }
}