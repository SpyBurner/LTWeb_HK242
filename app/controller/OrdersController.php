<?php
namespace controller;

use core\Controller;
use core\SessionHelper;
use service\AuthService;
use service\OrderService;
use service\ContactService;
use service\RateProductService;
use model\RateProductModel;

class OrdersController extends Controller {
    public function myOrders() {
        if (!$this->isAuthenticate()) {
            $this->redirectWithMessage('/account/login', ['error' => 'Please login to view your orders']);
            return;
        }

        $authResult = AuthService::validateSession();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/account/login', ['error' => 'Invalid session. Please login again.']);
            return;
        }

        $userId = $authResult['user']['userid'];

        $filters = [
            'search' => $this->get('search'),
            'status' => $this->get('status'),
        ];

        $ordersResult = OrderService::findAllWithDetails($filters);
        
        $this->render('orders/my-orders', [
            'orders' => $ordersResult['success'] ? $ordersResult['data'] : [],
            'filters' => $filters,
            'messages' => SessionHelper::getFlash('messages') ?? [],
            'error' => $ordersResult['success'] ? null : $ordersResult['message']
        ]);
    }

    public function orderDetail($orderId) {
        if (!$this->isAuthenticate()) {
            $this->redirectWithMessage('/account/login', ['error' => 'Please login to view order details']);
            return;
        }

        $authResult = AuthService::validateSession();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/account/login', ['error' => 'Invalid session. Please login again.']);
            return;
        }

        $userId = $authResult['user']['userid'];

        $orderResult = OrderService::getOrderById($orderId);
        if (!$orderResult['success']) {
            $this->redirectWithMessage('/orders/my-orders', ['error' => $orderResult['message']]);
            return;
        }

        $order = $orderResult['data'];

        // Fetch contact information using contactId from the order
        $contact = null;
        if ($order->getContactId()) {
            $contactResult = ContactService::findById($order->getContactId());
            if ($contactResult['success']) {
                $contact = $contactResult['data'];
            }
        }

        $existingRatings = [];
        if ($order->getStatus() === 'Delivered') {
            $ratingsResult = RateProductService::findByOrderId($orderId);
            if ($ratingsResult['success']) {
                foreach ($ratingsResult['data'] as $rating) {
                    $existingRatings[$rating->getProductid()] = $rating;
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $this->post('product_id');
            $rating = $this->post('rating');
            $comment = $this->post('comment');

            if ($order->getStatus() === 'Delivered' && $productId && $rating) {
                $rateModel = new RateProductModel(
                    $orderId,
                    $productId,
                    $rating,
                    $comment,
                    date('Y-m-d H:i:s')
                );

                $saveResult = isset($existingRatings[$productId]) 
                    ? RateProductService::update($rateModel)
                    : RateProductService::save($rateModel);

                if ($saveResult['success']) {
                    $this->redirectWithMessage("/orders/detail/$orderId", [
                        'success' => 'Rating submitted successfully!'
                    ]);
                } else {
                    SessionHelper::setFlash('messages', [
                        'error' => $saveResult['message']
                    ]);
                }
            }
        }

        $this->render('orders/order-detail', [
            'order' => $order,
            'contact' => $contact,
            'existingRatings' => $existingRatings,
            'messages' => SessionHelper::getFlash('messages') ?? []
        ]);
    }
}