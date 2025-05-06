<?php
namespace controller;

use core\Controller;
use core\SessionHelper;
use service\AuthService;
use service\OrderService;
use service\ContactService;
use model\ContactModel;

class CartController extends BaseController  {
    public function index() {
        $this->requireAuth(false);


        $result = AuthService::getCurrentUser();
        if (!$result['success']){
            $this->redirectWithMessage('/',[
                'error' => $result['message']
            ]);
        }

        $userId = $result['user']->getUserid();
        $cartResult = OrderService::getCartByUserId($userId);

        $this->render('cart/cart', [
            'cart' => $cartResult['success'] && $cartResult['data'] ? $cartResult['data'] : null,
            'messages' => SessionHelper::getFlash('messages') ?? []
        ]);
    }

    public function add($productId = null) {
        $this->requireAuth(false);


        $result = AuthService::getCurrentUser();
        if (!$result['success']){
            $this->redirectWithMessage('/',[
                'error' => $result['message']
            ]);
        }

        $userId = $result['user']->getUserid();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $productId = $this->get('product_id') ?? $productId;
            $amount = (int)$this->get('amount', 1);

            if (empty($productId)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Product ID is required']);
                exit;
            }

            $result = OrderService::addToCart($userId, $productId, $amount);

            if ($result['success']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Product added to cart']);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $result['message']]);
                exit;
            }
        } else {
            $this->redirectWithMessage('/products', [
                'error' => 'Invalid request method'
            ]);
        }
    }

    public function remove($productId = null) {
        $this->requireAuth(false);


        $result = AuthService::getCurrentUser();
        if (!$result['success']){
            $this->redirectWithMessage('/',[
                'error' => $result['message']
            ]);
        }

        $userId = $result['user']->getUserid();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $productId = $this->get('product_id') ?? $productId;

            if (empty($productId)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Product ID is required']);
                exit;
            }

            $cartResult = OrderService::getCartByUserId($userId);
            if (!$cartResult['success'] || !$cartResult['data']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Cart not found']);
                exit;
            }

            $orderId = $cartResult['data']->getOrderid();

            $result = OrderService::removeProductFromOrder($orderId, $productId);

            if ($result['success']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $result['message']]);
                exit;
            }
        } else {
            $this->redirectWithMessage('/cart', [
                'error' => 'Invalid request method'
            ]);
        }
    }

    public function reduce($productId = null) {
        $this->requireAuth(false);


        $result = AuthService::getCurrentUser();
        if (!$result['success']){
            $this->redirectWithMessage('/',[
                'error' => $result['message']
            ]);
        }

        $userId = $result['user']->getUserid();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $productId = $this->get('product_id') ?? $productId;
            $amount = (int)$this->get('amount', 1);

            if (empty($productId)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Product ID is required']);
                exit;
            }

            $cartResult = OrderService::getCartByUserId($userId);
            if (!$cartResult['success'] || !$cartResult['data']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Cart not found']);
                exit;
            }

            $orderId = $cartResult['data']->getOrderid();

            $result = OrderService::reduceProductInOrder($orderId, $productId, $amount);

            if ($result['success']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Product quantity reduced']);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $result['message']]);
                exit;
            }
        } else {
            $this->redirectWithMessage('/cart', [
                'error' => 'Invalid request method'
            ]);
        }
    }

    public function checkout() {
        $this->requireAuth(false);


        $result = AuthService::getCurrentUser();
        if (!$result['success']){
            $this->redirectWithMessage('/',[
                'error' => $result['message']
            ]);
        }

        $userId = $result['user']->getUserid();
        $cartResult = OrderService::getCartByUserId($userId);
        if (!$cartResult['success'] || !$cartResult['data'] || empty($cartResult['data']->products)) {
            $this->redirectWithMessage('/cart', [
                'error' => 'Your cart is empty'
            ]);
            return;
        }

        $this->render('checkout/checkout', [
            'cart' => $cartResult['data'],
            'userId' => $userId,
            'messages' => SessionHelper::getFlash('messages') ?? []
        ]);
    }

    public function paymentValid() {
        $this->requireAuth(false);


        $result = AuthService::getCurrentUser();
        if (!$result['success']){
            $this->redirectWithMessage('/',[
                'error' => $result['message']
            ]);
        }

        $userId = $result['user']->getUserid();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactId = $this->post('contactId');
            $email = $this->post('email');
            $note = $this->post('note');
            $paymentMethod = $this->post('payment_method');

            if (empty($contactId) || empty($email) || empty($paymentMethod)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
                exit;
            }

            // Step 1: Verify contact exists and belongs to the user
            $contactResult = ContactService::findById($contactId);
            if (!$contactResult['success'] || $contactResult['data']->getCustomerId() != $userId) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid contact selected']);
                exit;
            }

            // Step 2: Get cart
            $cartResult = OrderService::getCartByUserId($userId);
            if (!$cartResult['success'] || !$cartResult['data']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Cart not found']);
                exit;
            }

            $orderId = $cartResult['data']->getOrderid();

            // Step 3: Update contactId in the order
            $contactUpdateResult = OrderService::updateContactId($orderId, $contactId);
            if (!$contactUpdateResult['success']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $contactUpdateResult['message']]);
                exit;
            }

            // Step 4: Update product stock and bought counts
            $stockResult = OrderService::updateProductStockAndBought($orderId);
            if (!$stockResult['success']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $stockResult['message']]);
                exit;
            }

            // Step 5: Update order status
            $updateResult = OrderService::updateStatus($orderId, 'Preparing');
            if (!$updateResult['success']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $updateResult['message']]);
                exit;
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Order placed successfully', 'orderId' => $orderId]);
            exit;
        } else {
            $this->redirectWithMessage('/checkout', [
                'error' => 'Invalid request method'
            ]);
        }
    }

    public function confirmation($orderId = null) {
        $this->requireAuth(false);


        $result = AuthService::getCurrentUser();
        if (!$result['success']){
            $this->redirectWithMessage('/',[
                'error' => $result['message']
            ]);
        }

        $userId = $result['user']->getUserid();
        if (empty($orderId)) {
            $this->redirectWithMessage('/cart', [
                'error' => 'Invalid order ID'
            ]);
            return;
        }

        // Lấy thông tin đơn hàng
        $orderResult = OrderService::getOrderById($orderId);
        if (!$orderResult['success'] || !$orderResult['data'] || $orderResult['data']->getCustomerid() != $userId) {
            $this->redirectWithMessage('/cart', [
                'error' => 'Order not found or you do not have permission to view it'
            ]);
            return;
        }

        // Lấy thông tin liên hệ mới nhất của user
        $contactResult = ContactService::findAllByCustomerId($userId);
        $contact = !empty($contactResult['data']) ? end($contactResult['data']) : null; // Lấy contact cuối cùng

        $this->render('confirmation/confirmation', [
            'order' => $orderResult['data'],
            'contact' => $contact,
            'messages' => SessionHelper::getFlash('messages') ?? []
        ]);
    }
}