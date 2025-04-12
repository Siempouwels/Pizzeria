<?php
require_once __DIR__.'/../models/User.php';

class UserAdminController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        $users = $this->userModel->getAll();
        include __DIR__.'/../views/admin/users.php';
    }

    public function editForm(string $username): void
    {
        $user = $this->userModel->findByUsername($username);
        if (! $user) {
            http_response_code(404);
            echo "Gebruiker niet gevonden.";
            return;
        }
        include __DIR__.'/../views/admin/user_form.php';
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