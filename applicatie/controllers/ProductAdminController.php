<?php

require_once __DIR__ . '/../models/Product.php';

class ProductAdminController
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function index(): void
    {
        $products = $this->productModel->getAllWithType();
        include __DIR__ . '/../views/admin/product/index.php';
    }

    public function createForm(): void
    {
        $types = $this->productModel->getAllTypes();
        include __DIR__ . '/../views/admin/product/form.php';
    }

    public function store(): void
    {
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $type = $_POST['type'] ?? '';

        if ($name && is_numeric($price) && $type) {
            $this->productModel->insert($name, floatval($price), $type);
        }

        header('Location: /admin/producten');
        exit;
    }

    public function editForm(string $name): void
    {
        $product = $this->productModel->findByName($name);
        $types = $this->productModel->getAllTypes();

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

        if ($name && is_numeric($price) && $type) {
            $this->productModel->update($name, floatval($price), $type);
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
