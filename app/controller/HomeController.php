<?php
namespace controller;

use core\Controller;
use service\AuthService;

class HomeController extends UserController {
    public function __construct() {
        parent::__construct();
    }

    public function index(): void
    {
        var_dump($this->headerData);
        // Render the home page
        $this->render('home/home', [
            'title' => 'Home Page',
        ]);
    }

    public function sendContactForm() {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']  === 'POST'){
            $name = $this->post('name');
            $email = $this->post('email');
            $phone = $this->post('phone');
            $message = $this->post('message');

            // Process the contact form data (e.g., save to database, send email, etc.)
            // For now, just echo the data for demonstration purposes
            echo "Name: $name\n";
            echo "Email: $email\n";
            echo "Phone: $phone\n";
            echo "Message: $message\n";
        }
        else{
            // Redirect to the home page or show an error message
            $this->redirectWithMessage('/', ['error' => 'Invalid request']);
        }
    }
}



// services:
// - search products -> /products?search=keyword&category=category&sortby=sortby&page=page&limit=limit
// - search by category
// - view top N newest/top rating/best seller
// - search by filter (newest/top rating/best seller)
// - send contact form