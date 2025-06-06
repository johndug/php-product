<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Database\Database;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderItem;

class Order
{
    private $conn;
    private $id;
    private $userId;
    private $totalAmount;
    private $status;
    private $createdAt;

    public function __construct() 
    {
        $this->conn = new Database();
    }

    public function createOrder(int $userId, string $status = 'pending'): int
    {
        $user = User::getById($userId);

        if (!$user) {
            throw new \Exception("User not found");
        }

        $cart = Cart::getCart();

        if (empty($cart)) {
            throw new \Exception("Cart is empty");
        }

        $this->conn->execute("INSERT INTO orders (user_id, status) VALUES (:user_id, :status)", [
            'user_id' => $userId,
            'status' => $status,
        ]);

        $this->id = $this->conn->lastInsertId();

        $totalAmount = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::findById($productId);

            if (!$product) {
                throw new \Exception("Product not found");
            }
            
            $orderItem = new OrderItem();
            $orderItem->createOrderItem($this->id, $productId, $quantity, $product->getPrice());

            $totalAmount += $product->getPrice() * $quantity;
        }

        $this->conn->execute("UPDATE orders SET total_amount = :total_amount WHERE id = :id", [
            'total_amount' => $totalAmount,
            'id' => $this->id,
        ]);

        return $this->id;
    }

    public static function getById(int $orderId): array
    {
        $instance = new self();
        $result = $instance->conn->fetch("SELECT * FROM orders WHERE id = :id", [
            'id' => $orderId,
        ]);

        if (!$result) {
            return [];
        }

        return $result;
    }
}