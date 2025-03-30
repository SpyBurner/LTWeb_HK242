<?php

use core\Controller;
use service\AuthService;

class AuthController extends Controller {
    private $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            session_start();
            $username = $this->post('username');
            $email = $this->post('email');
            $password = $this->post('password');
            $password_confirm = $this->post('password_confirm');
            $accept_tos = $this->post('accept_tos');
            $_SESSION['form_data'] = compact('username', 'email', 'accept_tos');
            if (!$accept_tos) {
                $_SESSION['error'] = "You must accept the terms of service";
                header("Location: /account/register");
                exit;
            }
            if ($password !== $password_confirm) {
                $_SESSION['error'] = "Password and confirm password do not match";
                header("Location: /account/register");
                exit;
            }

            $result  = $this->authService->register($username, $email, $password);

            if (!$result['success']) {
                $_SESSION['error'] = $result['message'];
                header("Location: /account/register");
                exit;
            }
            $_SESSION['success'] = "Account created successfully";
            header("Location: /account/register");
            exit;

        } else {
            require_once __DIR__ . "/../view/account/register.php";
        }
    }

    public function login() {
        require_once __DIR__ . "/../view/account/login.php";
    }
}
