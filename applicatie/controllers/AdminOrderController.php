<?php
require_once __DIR__.'/../models/Order.php';

class AdminOrderController
{
    public function index(): void
    {
        $orderModel = new Order();
        $orders = $orderModel->getAllOrders();
        $orderItems = $orderModel->getOrderItems($orders);

        include __DIR__.'/../views/admin/orders.php';
    }
}
