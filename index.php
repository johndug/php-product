<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\UserController;
use App\Controllers\OrderController;

// $cart = new Cart();

// $apple = Product::findById(1);

// $cart->addToCart($apple->getId(), 3);

// $orange = Product::findById(2);

// $cart->addToCart($orange->getId(), 1);

// print_r($cart->showCart());

// $user = new User();
// $user->createUser('John Doe', 'john@example.com', 'password');

// $user = $user->getByEmail('john@example.com');

// print_r($user);

// $order = new Order();
// $orderId = $order->createOrder($user->getId());

// $order = Order::getById($orderId);

// print_r($order);

$products = ProductController::index();

$apple = ProductController::show(1);

print_r($apple);

$user = UserController::getByEmail('john@example.com');

print_r($user);

$cart = CartController::addToCart($apple->getId(), 3);

$orange = ProductController::show(2);

print_r($orange);

$cart = CartController::addToCart($orange->getId(), 1);


$cart = CartController::showCart();

print_r($cart);

// order from cart

$order = OrderController::createOrder($user->getId());

print_r($order);

$showOrder = OrderController::getOrder($order->getId());

print_r($showOrder);

$orders = OrderController::getOrders($user->getId());

print_r($orders);





