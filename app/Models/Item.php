<?php
declare(strict_types=1);

namespace app\Models;

class Item extends BaseModel
{
    protected string $table = 'items';

    public function create(string $name, string $description, float $price): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO items (name, description, price) VALUES (:name, :description, :price)"
        );
        return $stmt->execute([
            ':name'        => $name,
            ':description' => $description,
            ':price'       => $price,
        ]);
    }

    public function update(int $id, string $name, string $description, float $price): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE items SET name = :name, description = :description, price = :price
             WHERE id = :id"
        );
        return $stmt->execute([
            ':name'        => $name,
            ':description' => $description,
            ':price'       => $price,
            ':id'          => $id,
        ]);
    }
}
