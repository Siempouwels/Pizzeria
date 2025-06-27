<?php

require_once __DIR__ . '/../models/Order.php';

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
