<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Product;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;

$cart = new Cart();

$apple = Product::findById(1);

$cart->addToCart($apple->getId(), 3);

$orange = Product::findById(2);

$cart->addToCart($orange->getId(), 1);

print_r($cart->showCart());

$user = new User();
$user->createUser('John Doe', 'john@example.com', 'password');

$user = $user->getByEmail('john@example.com');

print_r($user);

$order = new Order();
$orderId = $order->createOrder($user->getId());

$order = Order::getById($orderId);

print_r($order);




