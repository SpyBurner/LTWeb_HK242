<?php
require_once __DIR__ . "/../../core/Controller.php";
require_once __DIR__ . "/../model/UserModel.php";

class AuthController extends Controller {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $this->post('username');
            $email = $this->post('email');
            $password = $this->post('password');
            $password_confirm = $this->post('password_confirm');
            $accept_tos = $this->post('accept_tos');

            if (!$accept_tos && !isset($error)) {
                $error = "You must accept the terms of service";
            }

            if ($password !== $password_confirm && !isset($error)) {
                $error = "Password and confirm password do not match";
            }

            if (isset($error)) {
                header("Location: /account/register?error=$error");
            }
            else {
                $user = new User(null, $username, $email, $password);
                $user->save();
                header("Location: /account/register?success=Account+created+successfully");
            }
        }
        else{
            require_once __DIR__ . "/../view/account/register.php";
        }
    }
}