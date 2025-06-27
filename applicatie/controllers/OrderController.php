<?php

namespace App\Controllers;

use App\Models\Product;
use PDOException;


class OrderController
{
    public function index(): void
    {
        $errors = [];
        $success = false;
        $productModel = new Product();

        try {
            $items = $productModel->getAll();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $gekozen = $_POST['aantal'] ?? [];
                $gekozen_items = array_filter($gekozen, fn ($aantal) => intval($aantal) > 0);

                if (count($gekozen_items) === 0) {
                    $errors[] = "Selecteer minstens één product.";
                } else {
                    foreach ($gekozen_items as $itemName => $qty) {
                        if (isset($_SESSION['cart'][$itemName])) {
                            $_SESSION['cart'][$itemName] += intval($qty);
                        } else {
                            $_SESSION['cart'][$itemName] = intval($qty);
                        }
                    }
                    $success = true;
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Fout bij ophalen producten: " . $e->getMessage();
            $items = [];
        }

        include __DIR__ . '/../views/order/add_products.php';
    }

    public function addProductsToCart(): void
    {
        $errors = [];
        $success = false;
        $productModel = new Product();

        if (isset($_POST['aantal']) && is_array($_POST['aantal'])) {
            foreach ($_POST['aantal'] as $product => $qty) {
                $qty = intval($qty);
                if ($qty > 0) {
                    $_SESSION['cart'][$product] = ($_SESSION['cart'][$product] ?? 0) + $qty;
                    $success = true;
                }
            }

            if (! $success) {
                $errors[] = "⚠️ Geen producten toegevoegd. Voer minstens 1 hoeveelheid > 0 in.";
            }
        } else {
            $errors[] = "⚠️ Ongeldige invoer.";
        }

        // Toon opnieuw het formulier met eventueel feedback

        $items = $productModel->getAll();

        include __DIR__ . '/../views/order/add_products.php';
    }
}
