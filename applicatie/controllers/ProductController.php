<?php
require_once __DIR__.'/../models/Product.php';
require_once __DIR__.'/../models/Ingredient.php';

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

        $success = false;
        $errors = [];

        try {
            $items = $this->productModel->getAllWithType();
            $ingredients = $this->ingredientModel->getPerItem(array_column($items, 'item_name'));
        } catch (PDOException $e) {
            $errors[] = "❌ Fout bij ophalen data: ".$e->getMessage();
            $items = [];
            $ingredients = [];
        }

        include __DIR__.'/../views/products.php';
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
