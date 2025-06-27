<?php

namespace App\Models;

use Core\Model;

class Product extends Model
{
    public function getAllWithType(): array
    {
        $stmt = $this->db->prepare("
            SELECT i.name AS item_name, i.price, t.name AS type
            FROM Item i
            JOIN ItemType t ON i.type_id = t.name
            ORDER BY t.name, i.name
        ");
        $stmt->execute();
        return $stmt->fetchAll();
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

    public function findByName(string $name): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Item WHERE name = :name");
        $stmt->execute([':name' => $name]);
        return $stmt->fetch() ?: null;
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
    }
}
