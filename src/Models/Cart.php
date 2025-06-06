<?php

namespace App\Models;

session_start();

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Database\Database;

class Cart
{
    private $conn;

    public function __construct()
    {
        $this->conn = new Database();
    }

    public function addToCart(int $productId, int $quantity): void
    {
        $product = Product::findById($productId);

        if (!$product) {
            throw new \Exception("Product not found");
        }

        $cart = $_SESSION['cart'] ?? [];
        $cart[$productId] = $quantity;
        $_SESSION['cart'] = $cart;
    }

    public static function getCart(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public function removeFromCart(int $productId): void
    {
        $cart = $_SESSION['cart'] ?? [];
        unset($cart[$productId]);
        $_SESSION['cart'] = $cart;
    }

    public function clearCart(): void
    {
        $_SESSION['cart'] = [];
    }

    public function showCart(): ?array
    {
        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            return null;
        }

        $products = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::findById($productId);
            $products[] = [
                'product' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $quantity,
            ];
        }

        $total = array_sum(array_map(function ($product) {
            return $product['price'] * $product['quantity'];
        }, $products));

        return [
            'products' => $products,
            'total' => $total,
        ];
    }
}