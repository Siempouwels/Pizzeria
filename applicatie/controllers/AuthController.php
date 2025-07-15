<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    private User $userModel;
    private array $errors = [];

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginForm(): void
    {
        include __DIR__ . '/../views/auth/login.php';
    }

    public function handleLogin(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrfToken = $_POST['csrf_token'] ?? '';
        $this->errors = [];

        if ($username === '' || $password === '') {
            $this->errors[] = "Gebruikersnaam en wachtwoord zijn verplicht.";
        }

        if (empty($this->errors)) {
            $user = $this->userModel->findByCredentials($username, $password);

            if ($user) {
                session_regenerate_id(true);

                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['address'] = $user['address'];

                unset($_SESSION['csrf_token']);

                header('Location: /');
                exit;
            } else {
                $this->errors[] = "Ongeldige gebruikersnaam of wachtwoord.";
            }
        }

        $errors = $this->errors;
        include __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }

    public function registerForm(): void
    {
        $errors = $this->errors;
        include __DIR__ . '/../views/auth/register.php';
    }

    public function handleRegistration(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $address = $_POST['address'] ?? '';
        $role = 'Customer'; // standaardrol

        if ($username === '' || $password === '' || $firstName === '' || $lastName === '' || $address === '') {
            $this->errors[] = "Alle velden zijn verplicht.";
        }

        if ($this->userModel->exists($username)) {
            $this->errors[] = "Gebruikersnaam bestaat al.";
        }

        if (! empty($this->errors)) {
            $errors = $this->errors;
            include __DIR__ . '/../views/auth/register.php';
            return;
        }

        $this->userModel->create($username, $password, $firstName, $lastName, $address, $role);

        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name'] = $lastName;
        $_SESSION['address'] = $address;

        header('Location: /');
        exit;
    }
}
