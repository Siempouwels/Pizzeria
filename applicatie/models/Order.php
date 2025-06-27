<?php

namespace App\Models;

use Core\Model;

class Order extends Model
{
    public function getUserOrders(): array
    {
        if (isset($_SESSION['username'])) {
            $stmt = $this->db->prepare(
                "SELECT *
                 FROM Pizza_Order
                 WHERE client_username = :username
                 ORDER BY datetime DESC"
            );
            $stmt->execute([':username' => $_SESSION['username']]);

            return $stmt->fetchAll();
        }

        if (! empty($_SESSION['order_ids'])) {
            $ids = array_filter($_SESSION['order_ids'], 'is_numeric');
            if (! empty($ids)) {
                $in = implode(',', array_fill(0, count($ids), '?'));
                $stmt = $this->db->prepare(
                    "SELECT *
                     FROM Pizza_Order
                     WHERE order_id IN ($in)
                     ORDER BY datetime DESC"
                );
                $stmt->execute($ids);

                return $stmt->fetchAll();
            }
        }

        return [];
    }

    public function getOrderItems(array $orders): array
    {
        if (empty($orders)) {
            return [];
        }

        $ids = implode(',', array_column($orders, 'order_id'));

        $stmt = $this->db->query(
            "SELECT poi.order_id, poi.item_name, poi.quantity, i.price
             FROM Pizza_Order_Item poi
             JOIN Item i ON poi.item_name = i.name
             WHERE poi.order_id IN ($ids)"
        );

        $itemsPerOrder = [];
        foreach ($stmt as $item) {
            $itemsPerOrder[$item['order_id']][] = $item;
        }

        return $itemsPerOrder;
    }

    public function getAllOrders(): array
    {
        $stmt = $this->db->query(
            "SELECT *
             FROM Pizza_Order
             ORDER BY datetime DESC"
        );

        return $stmt->fetchAll();
    }
}
