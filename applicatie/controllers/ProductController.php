<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Ingredient;
use PDOException;

class ProductController
{
    private Product $productModel;
    private Ingredient $ingredientModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->ingredientModel = new Ingredient();
    }


    public function index(): void
    {
        if (! isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $errors  = [];
        $success = false;


        $page = 1;

        if (
            isset($_GET['page']) &&
            is_numeric($_GET['page']) &&
            (int)$_GET['page'] > 0
        ) {
            $page = (int) $_GET['page'];
        }

        $perPage = 10;

        try {
            $totalItems  = $this->productModel->countAll();
            $items       = $this->productModel->getPageWithType($page, $perPage);
            $ingredients = $this->ingredientModel->getPerItem(
                array_column($items, 'item_name')
            );
        } catch (PDOException $e) {
            $errors[]     = "❌ Fout bij ophalen data: " . $e->getMessage();
            $totalItems   = 0;
            $items        = [];
            $ingredients  = [];
        }

        $totalPages = (int) ceil($totalItems / $perPage);

        include __DIR__ . '/../views/product/index.php';
    }

    public function addToCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemName = $_POST['item_name'] ?? '';
            $quantity = max(0, intval($_POST['quantity'] ?? 0));

            if ($quantity > 0) {
                if (isset($_SESSION['cart'][$itemName])) {
                    $_SESSION['cart'][$itemName] += $quantity;
                } else {
                    $_SESSION['cart'][$itemName] = $quantity;
                }
                $_SESSION['success'] = true;
            } else {
                $_SESSION['errors'][] = "Voer een geldig aantal in voor $itemName.";
            }
        }

        header('Location: /');
        exit;
    }
}
