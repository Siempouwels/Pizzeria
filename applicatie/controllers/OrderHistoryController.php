<?php

namespace App\Controllers;

use App\Models\Order;

class OrderHistoryController
{
    private Order $orderModel;

    public function __construct()
    {
        $this->orderModel = new Order();
    }

    public function index(): void
    {
        $page = 1;

        if (
            isset($_GET['page']) &&
            is_numeric($_GET['page']) &&
            (int)$_GET['page'] > 0
        ) {
            $page = (int) $_GET['page'];
        }

        $perPage = 5;

        // totaal en data
        $totalOrders = $this->orderModel->countUserOrders();
        $orders      = $this->orderModel->getUserOrdersPage($page, $perPage);
        $orderItems  = $this->orderModel->getOrderItems($orders);

        $totalPages = (int) ceil($totalOrders / $perPage);

        include __DIR__ . '/../views/order/history.php';
    }
}
