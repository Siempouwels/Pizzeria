<?php
require_once __DIR__.'/../models/Product.php';

class OrderController
{
    public function addProductsView(): void
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
            $errors[] = "Fout bij ophalen producten: ".$e->getMessage();
            $items = [];
        }

        include __DIR__.'/../views/order/add_products.php';
    }
}
