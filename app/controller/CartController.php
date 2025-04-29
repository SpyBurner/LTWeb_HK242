<?php
namespace controller;

use core\Controller;
use core\SessionHelper;
use service\AuthService;
use service\OrderService;
use service\ContactService;
use model\ContactModel;

class CartController extends Controller {
    public function index() {
        if (!$this->isAuthenticate()) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Please login to view your cart'
            ]);
            return;
        }

        $authResult = AuthService::validateSession();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Invalid session. Please login again.'
            ]);
            return;
        }

        $userId = $authResult['user']['userid'];

        $cartResult = OrderService::getCartByUserId($userId);

        $this->render('cart/cart', [
            'cart' => $cartResult['success'] && $cartResult['data'] ? $cartResult['data'] : null,
            'messages' => SessionHelper::getFlash('messages') ?? []
        ]);
    }

    public function add($productId = null) {
        if (!$this->isAuthenticate()) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Please login to add products to your cart'
            ]);
            return;
        }

        $authResult = AuthService::validateSession();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Invalid session. Please login again.'
            ]);
            return;
        }

        $userId = $authResult['user']['userid'];

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
        if (!$this->isAuthenticate()) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Please login to modify your cart'
            ]);
            return;
        }

        $authResult = AuthService::validateSession();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Invalid session. Please login again.'
            ]);
            return;
        }

        $userId = $authResult['user']['userid'];

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
        if (!$this->isAuthenticate()) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Please login to modify your cart'
            ]);
            return;
        }

        $authResult = AuthService::validateSession();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Invalid session. Please login again.'
            ]);
            return;
        }

        $userId = $authResult['user']['userid'];

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
        if (!$this->isAuthenticate()) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Please login to checkout'
            ]);
            return;
        }

        $authResult = AuthService::validateSession();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Invalid session. Please login again.'
            ]);
            return;
        }

        $userId = $authResult['user']['userid'];

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

    public function paymentValid($userId = null) {
        if (!$this->isAuthenticate()) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Please login to proceed with payment'
            ]);
            return;
        }

        $authResult = AuthService::validateSession();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Invalid session. Please login again.'
            ]);
            return;
        }

        $userId = $authResult['user']['userid']; // Use authenticated userId

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->post('name');
            $phone = $this->post('phone');
            $email = $this->post('email');
            $address = $this->post('address');
            $note = $this->post('note');
            $paymentMethod = $this->post('payment_method');

            if (empty($name) || empty($phone) || empty($email) || empty($address) || empty($paymentMethod)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
                exit;
            }

            // Step 1: Create new Contact
            $contact = new ContactModel(null, $name, $phone, $address, $userId);
            $contactResult = ContactService::save($contact);

            if (!$contactResult['success']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $contactResult['message']]);
                exit;
            }

            // Step 2: Update Order status
            $cartResult = OrderService::getCartByUserId($userId);
            if (!$cartResult['success'] || !$cartResult['data']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Cart not found']);
                exit;
            }

            $orderId = $cartResult['data']->getOrderid();

            $updateResult = OrderService::updateStatus($orderId, 'Preparing');

            if ($updateResult['success']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Order placed successfully', 'orderId' => $orderId]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $updateResult['message']]);
                exit;
            }
        } else {
            $this->redirectWithMessage('/checkout', [
                'error' => 'Invalid request method'
            ]);
        }
    }
    public function confirmation($orderId = null) {
        if (!$this->isAuthenticate()) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Please login to view your order confirmation'
            ]);
            return;
        }

        $authResult = AuthService::validateSession();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/account/login', [
                'error' => 'Invalid session. Please login again.'
            ]);
            return;
        }

        $userId = $authResult['user']['userid'];

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