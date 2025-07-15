<?php

namespace App\Models;

use Core\Model;
use PDO;

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

            return $this->mapStatusLabels($stmt->fetchAll());
        }

        if (!empty($_SESSION['order_ids'])) {
            $ids = array_filter($_SESSION['order_ids'], 'is_numeric');

            if (!empty($ids)) {
                $in = implode(',', array_fill(0, count($ids), '?'));
                $stmt = $this->db->prepare(
                    "SELECT *
                     FROM Pizza_Order
                     WHERE order_id IN ($in)
                     ORDER BY datetime DESC"
                );
                $stmt->execute($ids);

                return $this->mapStatusLabels($stmt->fetchAll());
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

    public function getActiveOrdersPage(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = "
            SELECT *
            FROM Pizza_Order
            WHERE status IS NULL
               OR status != 3
            ORDER BY datetime DESC
            OFFSET :offset ROWS
            FETCH NEXT :perPage ROWS ONLY
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return $this->mapStatusLabels($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getAllOrders(): array
    {
        $stmt = $this->db->query(
            "SELECT *
             FROM Pizza_Order
             ORDER BY datetime DESC"
        );

        return $this->mapStatusLabels($stmt->fetchAll());
    }

    public function updateStatus(int $orderId, int $status): void
    {
        $stmt = $this->db->prepare("
            UPDATE Pizza_Order
               SET status = :status
             WHERE order_id = :order_id
        ");
        $stmt->execute([
            ':status'   => $status,
            ':order_id' => $orderId,
        ]);
    }

    public function countActiveOrders(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) AS cnt
                FROM Pizza_Order
                WHERE status IS NULL
                    OR status != 3
            "
        );
        $row = $stmt->fetch();
        return (int) ($row['cnt'] ?? 0);
    }

    public function countUserOrders(): int
    {
        if (isset($_SESSION['username'])) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) AS cnt
                FROM Pizza_Order
                WHERE client_username = :username
            ");
            $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['cnt'] ?? 0);
        }

        if (! empty($_SESSION['order_ids'])) {
            $ids = array_filter($_SESSION['order_ids'], 'is_numeric');
            if ($ids) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $stmt = $this->db->prepare("
                    SELECT COUNT(*) AS cnt
                    FROM Pizza_Order
                    WHERE order_id IN ($placeholders)
                ");
                $stmt->execute($ids);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int)($row['cnt'] ?? 0);
            }
        }

        return 0;
    }

    private static function getStatusLabel(?int $status): string
    {
        return match ($status) {
            null => 'Onbekend',
            1 => 'In afwachting',
            2 => 'In voorbereiding',
            3 => 'Afgeleverd',
            default => 'Onbekend',
        };
    }

    private function mapStatusLabels(array $orders): array
    {
        foreach ($orders as &$order) {
            $order['status_label'] = self::getStatusLabel($order['status'] ?? null);
        }
        return $orders;
    }

    public function getUserOrdersPage(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        if (isset($_SESSION['username'])) {
            $sql = "
                SELECT *
                  FROM Pizza_Order
                 WHERE client_username = :username
              ORDER BY datetime DESC
                OFFSET :offset ROWS
                FETCH NEXT :perPage ROWS ONLY
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
        } elseif (! empty($_SESSION['order_ids'])) {
            $ids = array_filter($_SESSION['order_ids'], 'is_numeric');
            if (! $ids) {
                return [];
            }
            $in   = implode(',', array_fill(0, count($ids), '?'));
            $sql  = "
                SELECT *
                  FROM Pizza_Order
                 WHERE order_id IN ($in)
              ORDER BY datetime DESC
                OFFSET :offset ROWS
                FETCH NEXT :perPage ROWS ONLY
            ";
            $stmt = $this->db->prepare($sql);
            // bind each ? in the IN(...)
            foreach (array_values($ids) as $i => $id) {
                $stmt->bindValue($i + 1, (int)$id, PDO::PARAM_INT);
            }
        } else {
            return [];
        }

        $stmt->bindValue(':offset',  $offset,  PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return $this->mapStatusLabels($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
