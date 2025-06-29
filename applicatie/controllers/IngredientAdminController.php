<?php

namespace App\Controllers;

use App\Models\Ingredient;

class IngredientAdminController
{
    private Ingredient $ingredientModel;

    public function __construct()
    {
        $this->ingredientModel = new Ingredient();
    }

    public function index(): void
    {
        $ingredients = $this->ingredientModel->getAll();
        include __DIR__ . '/../views/admin/ingredient/index.php';
    }

    public function createForm(): void
    {
        include __DIR__ . '/../views/admin/ingredient/form.php';
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');

        if ($name === '') {
            $_SESSION['errors'][] = "Naam mag niet leeg zijn.";
            header('Location: /admin/ingredienten/nieuw');
            exit;
        }

        $existing = $this->ingredientModel->findByName($name);

        if ($existing) {
            $_SESSION['errors'][] = "Ingrediënt '$name' bestaat al.";
            header('Location: /admin/ingredienten/nieuw');
            exit;
        }

        $this->ingredientModel->insert($name);
        $_SESSION['success'] = "Ingrediënt '$name' is toegevoegd.";
        header('Location: /admin/ingredienten');
        exit;
    }


    public function editForm(string $name): void
    {
        $ingredient = $this->ingredientModel->findByName($name);

        if (! $ingredient) {
            echo "Ingrediënt niet gevonden.";
            exit;
        }

        if ($ingredient['is_used']) {
            echo "Ingrediënt is gekoppeld aan producten en kan niet worden bewerkt.";
            exit;
        }

        include __DIR__ . '/../views/admin/ingredient/form.php';
    }

    public function update(): void
    {
        $originalName = $_POST['original_name'] ?? '';
        $newName = trim($_POST['name'] ?? '');

        if ($originalName !== '' && $newName !== '') {
            $ingredient = $this->ingredientModel->findByName($originalName);
            if ($ingredient && ! $ingredient['is_used']) {
                $this->ingredientModel->update($originalName, $newName);
            }
        }

        header('Location: /admin/ingredienten');
        exit;
    }

    public function delete(string $name): void
    {
        $ingredient = $this->ingredientModel->findByName($name);

        if ($ingredient && ! $ingredient['is_used']) {
            $this->ingredientModel->delete($name);
        }

        header('Location: /admin/ingredienten');
        exit;
    }
}
