<?php
namespace controller;

use core\Controller;
use service\AuthService;
use core\SessionHelper;

class AuthController extends Controller {
//    TESTING ZONE
    public function test_token(){
        $token = $_COOKIE['auth_token'] ?? null;

        if ($token) {
            $result = AuthService::validateSession();

            if ($result['success']) {
                foreach ($result['user'] as $key => $value) {
                    echo "$key: $value<br>";
                }
            } else {
                echo "Token is invalid or expired.";
            }
        } else {
            echo "No token found.";
        }
    }

//    TESTING ZONE
    public function register()
    {
        if ($this->isAuthenticate()){
            $this->redirectWithMessage("/", [
                'success' => "You are already logged in"
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            session_start();
            $username = $this->post('username');
            $email = $this->post('email');
            $password = $this->post('password');
            $password_confirm = $this->post('password_confirm');
            $accept_tos = $this->post('accept_tos');

//            Set flash data to keep the form data in case of error and redirection
            SessionHelper::setFlash('form_data', compact('username', 'email', 'accept_tos'));

            if (!$accept_tos) {
                $this->redirectWithMessage('/account/register', [
                    'error' => "You must accept the terms of service"
                ]);
            }
            if ($password !== $password_confirm) {
                $this->redirectWithMessage('/account/register', [
                    'error' => "Password and confirm password do not match"
                ]);
            }

            $result  = AuthService::register($username, $email, $password);

            if (!$result['success']) {
                $this->redirectWithMessage('/account/register', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/account/login', [
                'success' => "Account created successfully. Please login."
            ]);
        } else {
            $this->render('account/register');
        }
    }

    public function login() {
        if ($this->isAuthenticate()){
            $this->redirectWithMessage("/", [
                'success' => "You are already logged in"
            ]);
        }


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $this->post('email');
            $password = $this->post('password');

            if (empty($email) || empty($password)) {
                $this->redirectWithMessage("/account/login", [
                    'error' => "Username and password are required"
                ]);
            }

            $result = AuthService::login($email, $password);

            if (!$result['success']) {
                $this->redirectWithMessage("/account/login", [
                    'error' => $result['message']
                ]);
            }

            $token = $result['token'];
            $expires = time() + TOKEN_LIFETIME;

            $setcookie_result = setcookie("auth_token", $token, [
                'expires' => $expires,
                'path' => '/',           // Available across the whole domain
                'domain' => 'localhost',          // Set to your domain (optional)
//                    'secure' => true,        // Only send over HTTPS
//                    'httponly' => true,      // Prevent JavaScript access (XSS protection)
            ]);

            if (!$setcookie_result) {
                $this->redirectWithMessage("/account/login", [
                    'error' => "Failed to set cookie"
                ]);
            }

            $this->redirectWithMessage("/", [
                'success' => "Login successful"
            ]);

        }
        else{
            $this->render('account/login', []);
        }
    }
}