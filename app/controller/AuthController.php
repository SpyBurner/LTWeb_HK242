<?php
namespace controller;

use core\Controller;
use core\Logger;
use service\AuthService;
use core\SessionHelper;
use service\UserService;

class AuthController extends BaseController {
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
    public function register(): void
    {
        if ($this->isAuthenticate()['success']){
            $this->redirectWithMessage("/", [
                'success' => "You are already logged in"
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    public function login(): void
    {
        if ($this->isAuthenticate()['success']){
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
//                    'secure' => true, // Only send over HTTPS
                'httponly' => true,      // Prevent JavaScript access (XSS protection)
            ]);

            if (!$setcookie_result) {
                $this->redirectWithMessage("/account/login", [
                    'error' => "Failed to set cookie"
                ]);
            }

            if (isset($_SESSION['return_to'])) {
                $return_to = SessionHelper::getFlash('return_to');
                $this->redirect($return_to);
            }


            $this->redirectWithMessage("/", [
                'success' => "Login successful"
            ]);

        }
        else{
            $this->render('account/login', []);
        }
    }

    public function logout() {
        AuthService::logout();
        $this->redirectWithMessage("/", [
            'success' => "Logout successful"
        ]);
    }

    public function forgetPassword(){
        $data = [];

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  == 'POST'){
            $email = $this->post('email');
            Logger::log("Forget password request for email: $email");
            $result = UserService::findByEmail($email);
            if (!$result['success']) {
                $this->redirectWithMessage('/account/forget_password', [
                    'error' => $result['message']
                ]);
            }

            $user = $result['data'];

            $result = AuthService::generateResetToken($user);
            if (!$result['success']) {
                $this->redirectWithMessage('/account/forget_password', [
                    'error' => $result['message']
                ]);
            }

            $token = $result['token'];

            $resetLink = "http://localhost/account/reset_password?token=$token";
            $subject = "CakeZone Password Reset";
            $message = "Hi,\n\nClick this link to reset your password: $resetLink\n\nThis link will expire in 1 hour.";
            $headers = "From: no-reply@cakezone.com";

            $result = mail($email, $subject, $message, $headers);
            if (!$result) {
                $this->redirectWithMessage('/account/forget_password', [
                    'error' => "Failed to send email"
                ]);
            }
            $this->redirectWithMessage('/account/login', [
                'success' => "Password reset link sent to your email, please check within 1 hour."
            ]);

        }

        $this->render('account/forget_password', $data);
    }

    public function resetPassword(){
        $token = $this->get('token');

        $result = UserService::findByToken($token);
        if (!$result['success']) {
            $this->redirectWithMessage('/account/login', [
                'error' => $result['message']
            ]);
        }

        $user = $result['data'];

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $this->post('new_password');
            $password_confirm = $this->post('password_confirm');

            if (empty($password) || empty($password_confirm)) {
                $this->redirectWithMessage('/account/reset_password', [
                    'error' => "Password and confirm password are required"
                ]);
            }

            if ($password !== $password_confirm) {
                $this->redirectWithMessage('/account/reset_password', [
                    'error' => "Password and confirm password do not match"
                ]);
            }

            $result = AuthService::resetPassword($user, $password);
            if (!$result['success']) {
                $this->redirectWithMessage('/account/login', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/account/login', [
                'success' => "Password reset successfully. Please login."
            ]);
        }

        $this->render('account/reset_password', []);
    }
}