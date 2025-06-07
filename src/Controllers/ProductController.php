<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Product;

class ProductController
{
    public static function index(): array
    {
        $products = Product::getAll();
        return $products;
    }

    public static function show(int $id): ?Product
    {
        $product = Product::findById($id);
        return $product;
    }

    public function create(array $data): ?Product
    {
        $product = new Product();
        $product->insert($data);
        return $product;
    }

    public function update(int $id, array $data): ?Product
    {
        $product = Product::findById($id);
        $product->update($data);
        return $product;
    }

    public function softDelete(int $id): bool
    {
        return Product::softDelete($id);
    }

    public function restore(int $id): bool
    {
        return Product::restore($id);
    }
}