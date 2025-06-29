<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Ingredient;

class ProductAdminController
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
        $products = $this->productModel->getAllWithType();
        include __DIR__ . '/../views/admin/product/index.php';
    }

    public function createForm(): void
    {
        $types = $this->productModel->getAllTypes();
        $allIngredients = $this->ingredientModel->getAll();
        $selectedIngredients = [];
        include __DIR__ . '/../views/admin/product/form.php';
    }

    public function store(): void
    {
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $type = $_POST['type'] ?? '';
        $ingredients = $_POST['ingredients'] ?? [];

        if ($name && is_numeric($price) && $type) {
            $this->productModel->insert($name, floatval($price), $type);
            $this->productModel->setIngredients($name, $ingredients);
        }

        header('Location: /admin/producten');
        exit;
    }

    public function editForm(string $name): void
    {
        $product = $this->productModel->findByName($name);
        $types = $this->productModel->getAllTypes();
        $allIngredients = $this->ingredientModel->getAll();
        $selectedIngredients = $this->productModel->getIngredientNames($name);

        if (! $product) {
            echo "Product niet gevonden.";
            exit;
        }

        include __DIR__ . '/../views/admin/product/form.php';
    }

    public function update(): void
    {
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $type = $_POST['type'] ?? '';
        $ingredients = $_POST['ingredients'] ?? [];

        if ($name && is_numeric($price) && $type) {
            $this->productModel->update($name, floatval($price), $type);
            $this->productModel->setIngredients($name, $ingredients);
        }

        header('Location: /admin/producten');
        exit;
    }

    public function delete(string $name): void
    {
        $this->productModel->delete($name);
        header('Location: /admin/producten');
        exit;
    }
}
