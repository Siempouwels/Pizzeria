<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model
{
    public function findByCredentials(string $username, string $password): ?array
    {
        $sql = "SELECT TOP 1 *
            FROM [User]
            WHERE username = :username
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return null;
    }

    public function exists(string $username): bool
    {
        $stmt = $this->db->prepare(
            "SELECT TOP 1 
                1 FROM [User] 
            WHERE username = :username
            "
        );

        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function create(
        string $username,
        string $password,
        string $firstName,
        string $lastName,
        string $address,
        string $role
    ): void {
        $sql = "INSERT INTO [User] (
                username,
                password,
                first_name,
                last_name,
                [address],
                [role]
            ) VALUES (
                :username,
                :password,
                :first_name,
                :last_name,
                :address,
                :role
            )
        ";

        $stmt = $this->db->prepare($sql);
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
        $sql = "SELECT
                username,
                first_name,
                last_name,
                [address],
                [role]
            FROM [User]
            ORDER BY [role], username
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT
                id,
                username,
                first_name,
                last_name,
                [address],
                [role]
            FROM [User]
            WHERE username = :username
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateRole(string $username, string $role): void
    {
        $sql = "UPDATE [User]
            SET [role] = :role
            WHERE username = :username
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':username', $username);

        $stmt->execute();
    }

    public function delete(string $username): void
    {
        $sql = "DELETE FROM [User]
            WHERE username = :username";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    }

    public function updateUser(
        string $username,
        string $role,
        string $firstName,
        string $lastName,
        ?string $address
    ): void {
        $sql = "UPDATE [User]
            SET [role]       = :role,
                first_name   = :first_name,
                last_name    = :last_name,
                [address]     = :address
            WHERE username = :username
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username'   => $username,
            ':role'       => $role,
            ':first_name' => $firstName,
            ':last_name'  => $lastName,
            ':address'    => $address,
        ]);
    }
}
