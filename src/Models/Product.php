<?php

namespace App\Models;

use App\Database\Database;

class Product
{
    private int $id;
    private string $name;
    private float $price;
    private int $stock;
    private $conn;

    public function __construct()
    {
        $this->conn = new Database();
    }

    public static function findById(int $id): ?self
    {
        $instance = new self();
        $result = $instance->conn->fetchOne("SELECT * FROM products WHERE id = :id", [
            'id' => $id,
        ]) ?? null;

        if (!$result) {
            return null;
        }

        $instance->setProperties($result);
        return $instance;
    }

    public function getAll(
        int $limit = 20,
        int $offset = 0,
    ): array
    {
        $result = $this->conn->fetch("SELECT * FROM products WHERE deleted_at IS NULL LIMIT :limit OFFSET :offset", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return array_map(function ($item) {
            return new Product($item['id'], $item['name'], $item['price'], $item['stock']);
        }, $result);
    }

    private function setProperties(array $result): void
    {
        $this->id = $result['id'];
        $this->name = $result['name'];
        $this->price = $result['price'];
        $this->stock = $result['stock'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}