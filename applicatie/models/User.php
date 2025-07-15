<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model
{
    public function findByCredentials(string $username, string $password): ?array
    {
        $sql = "
            SELECT TOP 1
                username,
                password,
                first_name,
                last_name,
                [address],
                [role]
            FROM [User]
            WHERE username = :username
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
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
        $sql = "
            SELECT 
                CASE WHEN EXISTS (
                    SELECT 1
                    FROM [User]
                    WHERE username = :username
                ) THEN 1 ELSE 0 END AS is_exists
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function create(
        string $username,
        string $password,
        string $firstName,
        string $lastName,
        string $address,
        string $role
    ): void {
        $sql = "
            INSERT INTO [User] (
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
        $stmt->bindValue(':username',   $username,  PDO::PARAM_STR);
        $stmt->bindValue(':password',   password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':last_name',  $lastName,  PDO::PARAM_STR);
        $stmt->bindValue(':address',    $address,   PDO::PARAM_STR);
        $stmt->bindValue(':role',       $role,      PDO::PARAM_STR);

        $stmt->execute();
    }

    public function getPage(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT
            username,
            first_name,
            last_name,
            [address],
            [role]
        FROM [User]
        ORDER BY [role], username
        OFFSET :offset ROWS
        FETCH NEXT :perPage ROWS ONLY";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':offset',  $offset,  PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(): int
    {
        $sql = "
            SELECT COUNT(*) AS total_count
            FROM [User]
        ";

        $row = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        return (int) $row['total_count'];
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT TOP 1
            username,
            first_name,
            last_name,
            [address],
            [role]
        FROM [User]
        WHERE username = :username";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function delete(string $username): void
    {
        $sql = "
            DELETE FROM [User]
            WHERE username = :username
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function updateUser(
        string $username,
        string $role,
        string $firstName,
        string $lastName,
        ?string $address
    ): void {
        $sql = "
            UPDATE [User]
            SET
                [role]      = :role,
                first_name  = :first_name,
                last_name   = :last_name,
                [address]   = :address
            WHERE username = :username
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username',   $username,  PDO::PARAM_STR);
        $stmt->bindValue(':role',       $role,      PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':last_name',  $lastName,  PDO::PARAM_STR);
        $stmt->bindValue(':address',    $address,   PDO::PARAM_STR | PDO::PARAM_NULL);

        $stmt->execute();
    }
}
