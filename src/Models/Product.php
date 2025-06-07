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

    public static function getAll(
        int $limit = 20,
        int $offset = 0,
    ): array
    {
        $instance = new self();
        $result = $instance->conn->fetch("SELECT * FROM products WHERE deleted_at IS NULL LIMIT :limit OFFSET :offset", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return $result;
    }

    public function setProperties(array $result): void
    {
        $this->id = $result['id'] ?? null;
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

    public function getStock(): int
    {
        return $this->stock;
    }

    public function insert(array $data): ?self
    {
        $this->conn->execute("INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)", [
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
        ]);

        $data['id'] = $this->conn->lastInsertId();

        $this->setProperties($data);

        return $this;
    }

    public function update(array $data): ?self
    {
        $product = self::findById($data['id']);

        if (!$product) {
            return null;
        }

        $product->setProperties($data);

        // check if attributes are loaded
        $this->conn->execute("UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id", [
            'id' => $data['id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
        ]);

        return $this;
    }

    public static function softDelete(int $id): bool
    {
        $instance = new self();
        if (!$instance->conn->execute("UPDATE products SET deleted_at = NOW() WHERE id = :id", [
            'id' => $id,
        ])) {
            return false;
        }

        return true;
    }

    public static function hardDelete(int $id): bool
    {
        $instance = new self();
        if (!$instance->conn->execute("DELETE FROM products WHERE id = :id", [
            'id' => $id,
        ])) {
            return false;
        }

        return true;
    }

    public static function restore(int $id): bool
    {
        $instance = new self();
        if (!$instance->conn->execute("UPDATE products SET deleted_at = NULL WHERE id = :id", [
            'id' => $id,
        ])) {
            return false;
        }

        return true;
    }
}