<?php
require_once __DIR__.'/../core/Model.php';

class User extends Model
{
    public function findByCredentials(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM [User] WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function exists(string $username): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM [User] WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function create(string $username, string $password, string $firstName, string $lastName, string $address, string $role): void
    {
        $stmt = $this->db->prepare("
        INSERT INTO [User] (username, password, first_name, last_name, address, role)
        VALUES (:username, :password, :first_name, :last_name, :address, :role)
    ");

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':role', $role);

        $stmt->execute();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT username, first_name, last_name, address, role FROM [User] ORDER BY role, username");
        return $stmt->fetchAll();
    }

}
