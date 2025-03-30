<?php
namespace controller;

use core\Controller;

class ProfileController extends Controller {
    public function index() {
        $this->requireAuth();

        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT PROFILECONTROLLER";
        }
        else{
            require_once __DIR__ . "/../view/profile/profile.php";
        }
    }
}