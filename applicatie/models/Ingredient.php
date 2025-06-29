<?php

namespace App\Models;

use Core\Model;
use PDO;

class Ingredient extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT i.name,
                   CASE
                       WHEN EXISTS (
                           SELECT 1
                           FROM Item_Ingredient ii
                           WHERE ii.ingredient_name = i.name
                       )
                       THEN 1
                       ELSE 0
                   END AS is_used
            FROM Ingredient i
            ORDER BY i.name ASC
        ");

        return $stmt->fetchAll();
    }

    public function getPerItem(array $itemNames): array
    {
        if (count($itemNames) === 0) {
            return [];
        }

        $placeholders = rtrim(str_repeat('?,', count($itemNames)), ',');
        $stmt = $this->db->prepare("
            SELECT ii.item_name, ing.name AS ingredient_name
            FROM Item_Ingredient ii
            JOIN Ingredient ing ON ii.ingredient_name = ing.name
            WHERE ii.item_name IN ($placeholders)
        ");
        $stmt->execute($itemNames);

        $result = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $result[$row['item_name']][] = $row['ingredient_name'];
        }

        return $result;
    }

    public function insert(string $name): void
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Ingredient WHERE name = :name");
        $stmt->execute(['name' => $name]);

        if ($stmt->fetchColumn() > 0) {
            $_SESSION['errors'][] = "Ingrediënt '$name' bestaat al.";
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO Ingredient (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);

        $_SESSION['success'] = "Ingrediënt '$name' is toegevoegd.";
    }


    public function update(string $oldName, string $newName): void
    {
        $stmt = $this->db->prepare("UPDATE Ingredient SET name = :newName WHERE name = :oldName");
        $stmt->execute(['newName' => $newName, 'oldName' => $oldName]);
    }

    public function delete(string $name): void
    {
        $stmt = $this->db->prepare("DELETE FROM Ingredient WHERE name = :name");
        $stmt->execute(['name' => $name]);
    }

    public function findByName(string $name): ?array
    {
        $stmt = $this->db->prepare("
            SELECT name,
                   CASE
                       WHEN EXISTS (
                           SELECT 1
                           FROM Item_Ingredient ii
                           WHERE ii.ingredient_name = Ingredient.name
                       )
                       THEN 1
                       ELSE 0
                   END AS is_used
            FROM Ingredient
            WHERE name = :name
        ");
        $stmt->execute(['name' => $name]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
