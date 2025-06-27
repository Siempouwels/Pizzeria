<?php

namespace App\Controllers;

use App\Models\Order;

class AdminOrderController
{
    public function index(): void
    {
        $orderModel = new Order();
        $orders = $orderModel->getAllOrders();
        $orderItems = $orderModel->getOrderItems($orders);

        include __DIR__ . '/../views/admin/order/index.php';
    }
}
