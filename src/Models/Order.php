<?php

namespace App\Models;

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

    public static function createOrder(int $userId, string $status = 'pending'): ?Order
    {
        $user = User::getById($userId);

        if (!$user) {
            throw new \Exception("User not found");
        }

        $cart = Cart::getCart();

        if (empty($cart)) {
            throw new \Exception("Cart is empty");
        }

        $instance = new self();
        $instance->conn->execute("INSERT INTO orders (user_id, status) VALUES (:user_id, :status)", [
            'user_id' => $userId,
            'status' => $status,
        ]);

        $instance->id = $instance->conn->lastInsertId();

        $totalAmount = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::findById($productId);

            if (!$product) {
                throw new \Exception("Product not found");
            }
            
            $orderItem = new OrderItem();
            $orderItem->createOrderItem($instance->id, $productId, $quantity, $product->getPrice());

            $totalAmount += $product->getPrice() * $quantity;
        }

        $instance->conn->execute("UPDATE orders SET total_amount = :total_amount WHERE id = :id", [
            'total_amount' => $totalAmount,
            'id' => $instance->id,
        ]);

        Cart::clearCart();

        return $instance;
    }

    public static function getById(int $orderId): array
    {
        $instance = new self();
        $result = $instance->conn->fetch("SELECT * FROM orders WHERE id = :id", [
            'id' => $orderId,
        ]);

        $result['items'] = $instance->conn->fetch("SELECT order_id, product_id, quantity, price FROM order_items WHERE order_id = :order_id", [
            'order_id' => $orderId,
        ]);
        

        if (!$result) {
            return [];
        }

        return $result;
    }

    public static function getOrders(int $userId): ?array
    {
        $instance = new self();
        $result = $instance->conn->fetch("SELECT * FROM orders WHERE user_id = :user_id", [
            'user_id' => $userId,
        ]);

        if (!$result) {
            return [];
        }

        foreach ($result as $order) {
            $order['items'] = $instance->conn->fetch("SELECT order_id, product_id, quantity, price FROM order_items WHERE order_id = :order_id", [
                'order_id' => $order['id'],
            ]);
        }

        return $result;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
}