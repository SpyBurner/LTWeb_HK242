<?php
namespace controller;

use core\SessionHelper;
use service\AuthService;
use service\OrderService;
use service\ContactService;
use service\RateProductService;
use model\RateProductModel;

class OrdersController extends BaseController
{
    /**
     * Display the list of the authenticated user's orders.
     */
    public function myOrders()
    {
        $this->requireAuth(false);

        $result = AuthService::getCurrentUser();
        if (!$result['success']) {
            $this->redirectWithMessage('/', [
                'error' => $result['message']
            ]);
        }

        $userId = $result['user']->getUserid();
        $filters = [
            'search' => $this->get('search'),
            'status' => $this->get('status'),
        ];

        // Use the new findUserOrders method
        $ordersResult = OrderService::findUserOrders($userId, $filters);
        
        $this->render('orders/my-orders', [
            'orders' => $ordersResult['success'] ? $ordersResult['data'] : [],
            'filters' => $filters,
            'messages' => SessionHelper::getFlash('messages') ?? [],
            'error' => $ordersResult['success'] ? null : $ordersResult['message']
        ]);
    }

    /**
     * Display details of a specific order and handle product rating submissions.
     *
     * @param int $orderId The ID of the order to display
     */
    public function orderDetail($orderId)
    {
        // Ensure user is authenticated
        $this->requireAuth(false);

        // Get current user
        $authResult = AuthService::getCurrentUser();
        if (!$authResult['success']) {
            $this->redirectWithMessage('/', [
                'error' => $authResult['message']
            ]);
            return;
        }

        $userId = $authResult['user']->getUserid();

        // Fetch order details
        $orderResult = OrderService::getOrderById($orderId);
        if (!$orderResult['success']) {
            $this->redirectWithMessage('/orders/my-orders', [
                'error' => $orderResult['message']
            ]);
            return;
        }

        $order = $orderResult['data'];

        // Verify that the order belongs to the current user
        if ($order->getCustomerid() !== $userId) {
            $this->redirectWithMessage('/orders/my-orders', [
                'error' => 'You do not have permission to view this order'
            ]);
            return;
        }

        // Fetch contact information if available
        $contact = null;
        if ($order->getContactId()) {
            $contactResult = ContactService::findById($order->getContactId());
            if ($contactResult['success']) {
                $contact = $contactResult['data'];
            }
        }

        // Fetch existing ratings for delivered orders
        $existingRatings = [];
        if ($order->getStatus() === 'Delivered') {
            $ratingsResult = RateProductService::findByOrderId($orderId);
            if ($ratingsResult['success']) {
                foreach ($ratingsResult['data'] as $rating) {
                    $existingRatings[$rating->getProductid()] = $rating;
                }
            }
        }

        // Handle rating submission
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

                // Update or save rating
                $saveResult = isset($existingRatings[$productId])
                    ? RateProductService::update($rateModel)
                    : RateProductService::save($rateModel);

                if ($saveResult['success']) {
                    $this->redirectWithMessage("/orders/detail/$orderId", [
                        'success' => 'Rating submitted successfully!'
                    ]);
                    return;
                } else {
                    SessionHelper::setFlash('messages', [
                        'error' => $saveResult['message']
                    ]);
                }
            } else {
                SessionHelper::setFlash('messages', [
                    'error' => 'Invalid rating submission'
                ]);
            }
        }

        // Render the order detail view
        $this->render('orders/order-detail', [
            'order' => $order,
            'contact' => $contact,
            'existingRatings' => $existingRatings,
            'messages' => SessionHelper::getFlash('messages') ?? []
        ]);
    }
}