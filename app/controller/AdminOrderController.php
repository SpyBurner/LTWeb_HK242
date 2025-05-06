<?php
namespace controller;

use core\Controller;
use core\SessionHelper;
use service\OrderService;

class AdminOrderController extends BaseController  {
    public function index() {
        $this->requireAuth(true);
        // Get filter parameters from request
        $filters = [
            'search' => $this->get('search') ?? $_GET['search'] ?? null,
            'status' => $this->get('status') ?? $_GET['status'] ?? null
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

    public function updateStatus() {
        $this->requireAuth(true);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $orderId = $input['orderId'] ?? null;
            $status = $input['status'] ?? null;

            if (empty($orderId) || empty($status)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid order ID or status']);
                exit;
            }

            $result = OrderService::updateStatus($orderId, $status);

            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
    }
}