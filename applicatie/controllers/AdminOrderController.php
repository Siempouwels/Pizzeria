<?php

namespace App\Controllers;

use App\Models\Order;
use Core\Auth;

class AdminOrderController
{
    public function index(): void
    {
        $orderModel  = new Order();
        $orders     = $orderModel->getActiveOrders();
        $orderItems = $orderModel->getOrderItems($orders);

        include __DIR__ . '/../views/admin/order/index.php';
    }

    public function updateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $status  = $_POST['status']   ?? null;

            if (is_numeric($orderId) && is_numeric($status)) {
                $orderModel = new Order();
                $orderModel->updateStatus((int)$orderId, (int)$status);
            }
        }

        header('Location: /admin/bestellingen');
        exit;
    }
}
