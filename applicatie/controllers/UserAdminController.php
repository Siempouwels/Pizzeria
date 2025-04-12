<?php
require_once __DIR__ . '/../models/User.php';

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
        include __DIR__ . '/../views/admin/users.php';
    }
}
