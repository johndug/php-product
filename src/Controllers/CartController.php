<?php

namespace App\Controllers;

use App\Models\Cart;

class CartController
{
    public static function addToCart(int $productId, int $quantity): bool
    {
        return Cart::addToCart($productId, $quantity) ?? false;
    }

    public static function showCart(): ?array
    {
        return Cart::showCart();
    }

    public static function removeFromCart(int $productId): bool
    {
        return Cart::removeFromCart($productId) ?? false;
    }
}