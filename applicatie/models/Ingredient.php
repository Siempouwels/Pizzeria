<?php

require_once __DIR__ . '/../core/Model.php';

class Ingredient extends Model
{
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
}
