<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Database\Database;

// seed products table

$conn = new Database();

$conn->execute("INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)", [
    'name' => 'Apple',
    'price' => 8,
    'stock' => 100,
]);

$conn->execute("INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)", [
    'name' => 'Orange',
    'price' => 4,
    'stock' => 150,
]);

