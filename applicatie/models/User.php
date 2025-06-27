<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    public function findByCredentials(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM [User] WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
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

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
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

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM [User] WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function updateRole(string $username, string $role): void
    {
        $stmt = $this->db->prepare("UPDATE [User] SET role = :role WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
    }

    public function delete(string $username): void
    {
        $stmt = $this->db->prepare("DELETE FROM [User] WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    }

    public function updateUser(string $username, string $role, string $firstName, string $lastName, ?string $address): void
    {
        $stmt = $this->db->prepare("
        UPDATE [User]
        SET role = :role, first_name = :first_name, last_name = :last_name, address = :address
        WHERE username = :username
    ");
        $stmt->execute([
            ':username' => $username,
            ':role' => $role,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':address' => $address,
        ]);
    }
}
