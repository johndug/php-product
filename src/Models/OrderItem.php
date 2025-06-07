<?php

namespace App\Models;

use App\Database\Database;

class OrderItem
{
    private $conn;

    public function __construct()
    {
        $this->conn = new Database();
    }

    public function createOrderItem(int $orderId, int $productId, int $quantity, float $price): void
    {
        $this->conn->execute("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)", [
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price,
        ]);
    }

    public static function getOrderItems(int $orderId): array
    {
        $instance = new self();
        $result = $instance->conn->fetch("SELECT * FROM order_items WHERE order_id = :order_id", [
            'order_id' => $orderId,
        ]);

        return $result ?? [];
    }
}