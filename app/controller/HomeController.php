<?php
require_once __DIR__ . "/../../core/Controller.php";

class HomeController extends Controller {
    public function index() {
        require_once __DIR__ . "/../view/home/home.php";
    }
}