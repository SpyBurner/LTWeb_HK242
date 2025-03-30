<?php
namespace service;

use model\UserModel;

//Special service, no IService implementation
class AuthService
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function register($username, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        return $this->userService->save(new UserModel(null, $username, $email, $hashed_password));
    }
}