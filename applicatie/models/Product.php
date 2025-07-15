<?php

namespace App\Models;

use Core\Model;
use PDO;

class Product extends Model
{
    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) FROM Item";
        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function getPageWithType(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT
                i.name       AS item_name,
                i.price,
                t.name       AS type
            FROM Item i
            JOIN ItemType t ON i.type_id = t.name
            ORDER BY t.name, i.name
            OFFSET :offset ROWS
            FETCH NEXT :perPage ROWS ONLY";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':offset', $offset, type: PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT name, price FROM Item ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getAllTypes(): array
    {
        $stmt = $this->db->query("SELECT name FROM ItemType ORDER BY name");
        return $stmt->fetchAll();
    }

    public function insert(string $name, float $price, string $type): void
    {
        $stmt = $this->db->prepare("INSERT INTO Item (name, price, type_id) VALUES (:name, :price, :type)");
        $stmt->execute([
            ':name' => $name,
            ':price' => $price,
            ':type' => $type,
        ]);
    }

    public function update(string $name, float $price, string $type): void
    {
        $stmt = $this->db->prepare("UPDATE Item SET price = :price, type_id = :type WHERE name = :name");
        $stmt->execute([
            ':price' => $price,
            ':type' => $type,
            ':name' => $name,
        ]);
    }

    public function delete(string $name): void
    {
        $stmt = $this->db->prepare("DELETE FROM Item WHERE name = :name");
        $stmt->execute([':name' => $name]);

        $stmt = $this->db->prepare("DELETE FROM Item_Ingredient WHERE item_name = :name");
        $stmt->execute([':name' => $name]);
    }

    public function findByName(string $name): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Item WHERE name = :name");
        $stmt->execute([':name' => $name]);
        return $stmt->fetch() ?: null;
    }

    public function setIngredients(string $productName, array $ingredientNames): void
    {
        $stmt = $this->db->prepare("DELETE FROM Item_Ingredient WHERE item_name = :name");
        $stmt->execute([':name' => $productName]);

        $stmt = $this->db->prepare("
            INSERT INTO Item_Ingredient (item_name, ingredient_name)
            VALUES (:item_name, :ingredient_name)
        ");

        foreach ($ingredientNames as $ingredientName) {
            $stmt->execute([
                ':item_name' => $productName,
                ':ingredient_name' => $ingredientName,
            ]);
        }
    }

    public function getIngredientNames(string $productName): array
    {
        $stmt = $this->db->prepare("
            SELECT ingredient_name
            FROM Item_Ingredient
            WHERE item_name = :name
        ");
        $stmt->execute([':name' => $productName]);
        return array_column($stmt->fetchAll(), 'ingredient_name');
    }
}
