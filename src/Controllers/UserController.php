<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public static function getById(int $id): ?User
    {
        return User::getById($id);
    }

    public static function getByEmail(string $email): ?User
    {
        return User::getByEmail($email);
    }

    public static function create(array $data): ?User
    {
        return User::create($data);
    }

    public static function update(int $id, array $data): ?User
    {
        return User::update($id, $data);
    }

    public static function delete(int $id): bool
    {
        return User::delete($id);
    }
}