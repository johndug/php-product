<?php

namespace App\Controllers;

use App\Models\Order;

class OrderController
{
    public static function createOrder(int $userId): ?Order
    {
        return Order::createOrder($userId);
    }

    public static function getOrder(int $orderId): ?array
    {
        return Order::getById($orderId);
    }

    public static function getOrders(int $userId): ?array
    {
        return Order::getOrders($userId);
    }
}