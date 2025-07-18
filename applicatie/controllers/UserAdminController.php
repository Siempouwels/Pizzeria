<?php

namespace App\Controllers;

use App\Models\User;

class UserAdminController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        $page = 1;
        
        if (
            isset($_GET['page']) &&
            is_numeric($_GET['page']) &&
            (int)$_GET['page'] > 0
        ) {
            $page = (int) $_GET['page'];
        }

        $perPage = 20;

        $totalUsers = $this->userModel->countAll();
        $users       = $this->userModel->getPage($page, $perPage);

        $totalPages = (int) ceil($totalUsers / $perPage);

        include __DIR__ . '/../views/admin/user/index.php';
    }

    public function editForm(string $username): void
    {
        $user = $this->userModel->findByUsername($username);
        if (! $user) {
            http_response_code(404);
            echo "Gebruiker niet gevonden.";
            return;
        }
        include __DIR__ . '/../views/admin/user/form.php';
    }

    public function update(): void
    {
        $username = $_POST['username'] ?? '';
        $role = $_POST['role'] ?? '';
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $address = $_POST['address'] ?? '';

        if ($username && $role && $firstName && $lastName) {
            $this->userModel->updateUser($username, $role, $firstName, $lastName, $address);
        }

        header('Location: /admin/gebruikers');
        exit;
    }

    public function delete(string $username): void
    {
        $this->userModel->delete($username);
        header('Location: /admin/gebruikers');
        exit;
    }
}
