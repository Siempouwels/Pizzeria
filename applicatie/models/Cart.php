<?php
require_once __DIR__.'/../core/Model.php';

class Cart extends Model
{
    public function handleActions(): array
    {
        $errors = [];
        $success = false;
        $productPrices = [];

        if (! empty($_SESSION['cart'])) {
            $placeholders = rtrim(str_repeat('?,', count($_SESSION['cart'])), ',');
            $stmt = $this->db->prepare("SELECT name, price FROM Item WHERE name IN ($placeholders)");
            $stmt->execute(array_keys($_SESSION['cart']));
            foreach ($stmt->fetchAll() as $row) {
                $productPrices[$row['name']] = $row['price'];
            }
        }

        // Aanpassingen in hoeveelheid
        if (isset($_POST['update_item'], $_POST['product'], $_POST['quantity'])) {
            $product = $_POST['product'];
            $quantity = max(0, intval($_POST['quantity']));
            if ($quantity === 0) {
                unset($_SESSION['cart'][$product]);
            } else {
                $_SESSION['cart'][$product] = $quantity;
            }
            header("Location: /winkelmandje");
            exit;
        }

        // Leegmaken
        if (isset($_POST['clear_cart'])) {
            unset($_SESSION['cart']);
            header("Location: /winkelmandje");
            exit;
        }

        // Bestelling plaatsen
        if (isset($_POST['confirm_order']) && ! empty($_SESSION['cart'])) {
            $firstName = $_SESSION['first_name'] ?? $_POST['first_name'] ?? '';
            $lastName = $_SESSION['last_name'] ?? $_POST['last_name'] ?? '';
            $address = $_SESSION['address'] ?? $_POST['address'] ?? '';
            $username = $_SESSION['username'] ?? null;

            if (! $firstName)
                $errors[] = "Voornaam is verplicht.";
            if (! $lastName)
                $errors[] = "Achternaam is verplicht.";
            if (! $address)
                $errors[] = "Adres is verplicht.";

            if (empty($errors)) {
                try {
                    $this->db->beginTransaction();

                    $personnel = $this->db->query("SELECT TOP 1 username FROM [User] WHERE role = 'Personnel' ORDER BY NEWID()")
                        ->fetchColumn();

                    if (! $personnel)
                        throw new Exception("Geen personeel beschikbaar.");

                    $stmt = $this->db->prepare("INSERT INTO Pizza_Order (client_username, client_name, personnel_username, datetime, status, address)
                                                VALUES (:username, :name, :personnel, GETDATE(), :status, :address)");
                    $stmt->execute([
                        ':username' => $username,
                        ':name' => "$firstName $lastName",
                        ':personnel' => $personnel,
                        ':status' => 1,
                        ':address' => $address,
                    ]);

                    $orderId = $this->db->lastInsertId();
                    $insert = $this->db->prepare("INSERT INTO Pizza_Order_Item (order_id, item_name, quantity)
                                                  VALUES (:order_id, :item_name, :quantity)");

                    foreach ($_SESSION['cart'] as $product => $qty) {
                        $insert->execute([
                            ':order_id' => $orderId,
                            ':item_name' => $product,
                            ':quantity' => $qty
                        ]);
                    }

                    $_SESSION['first_name'] = $firstName;
                    $_SESSION['last_name'] = $lastName;
                    $_SESSION['address'] = $address;
                    $_SESSION['order_ids'][] = $orderId;
                    unset($_SESSION['cart']);

                    $this->db->commit();
                    $success = true;
                } catch (Exception $e) {
                    if ($this->db->inTransaction())
                        $this->db->rollBack();
                    $errors[] = "❌ Fout: ".$e->getMessage();
                }
            }
        }

        return compact('success', 'errors', 'productPrices');
    }
}
