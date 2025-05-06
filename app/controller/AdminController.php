<?php
namespace controller;


use core\Controller;
use core\SessionHelper;
use service\ProductsService;
use service\CategoryService;
use service\OrderService;

class AdminController extends Controller {
    public function index() {
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT AdminController";
        }
        else{
            require_once __DIR__ . "/../view/admin/dashboard.php";
        }
    }

    public function contact(){
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT AdminController";
        }
        else{
            require_once __DIR__ . "/../view/admin/contact.php";
        }
    }

    public function qna(){
        if (isset($_SESSION['REQUEST_METHOD']) && $_SESSION['REQUEST_METHOD']  === 'POST'){
            echo "TODO HANDLE POST REQUEST AT AdminController";
        }
        else{
            require_once __DIR__ . "/../view/admin/qna.php";
        }
    }
    public function order() {
        // Get filter parameters from request
        $filters = [
            'search' => $this->get('search'),
            'status' => $this->get('status')
        ];
    
        // Get all orders with customer info and products
        $ordersResult = OrderService::findAllWithDetails($filters);
        
        // Get all possible statuses for filter dropdown
        $statuses = [
            '' => 'All statuses',
            'Preparing' => 'Preparing',
            'Prepared' => 'Prepared',
            'Delivering' => 'Delivering',
            'Delivered' => 'Delivered'
        ];
    
        $this->render('admin/order', [
            'orders' => $ordersResult['success'] ? $ordersResult['data'] : [],
            'statuses' => $statuses,
            'messages' => SessionHelper::getFlash('messages') ?? [],
            'searchTerm' => $filters['search'] ?? '',
            'selectedStatus' => $filters['status'] ?? ''
        ]);
    }
    
}