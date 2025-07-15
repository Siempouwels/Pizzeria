<?php

namespace App\Controllers;

use App\Models\Order;
use PDOException;

class AdminOrderController
{
    public function index(): void
    {
        $orderModel = new Order();

        $page = 1;
        
        if (
            isset($_GET['page']) &&
            is_numeric($_GET['page']) &&
            (int)$_GET['page'] > 0
        ) {
            $page = (int) $_GET['page'];
        }

        $perPage = 5;

        try {
            $totalOrders = $orderModel->countActiveOrders();
            $orders      = $orderModel->getActiveOrdersPage($page, $perPage);
            $orderItems  = $orderModel->getOrderItems($orders);
        } catch (PDOException $e) {
            $totalOrders = 0;
            $orders      = [];
            $orderItems  = [];
            $errors[]    = "❌ Fout bij ophalen bestellingen: " . $e->getMessage();
        }

        $totalPages = (int) ceil($totalOrders / $perPage);

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
