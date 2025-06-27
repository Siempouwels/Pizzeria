<?php

namespace App\Controllers;

use App\Models\Order;

class OrderHistoryController
{
    public function index(): void
    {
        $orderModel = new Order();
        $orders = $orderModel->getUserOrders();
        $orderItems = $orderModel->getOrderItems($orders);

        include __DIR__ . '/../views/order/history.php';
    }
}
