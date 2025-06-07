<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Database\Database;

class User
{
    private $conn;
    private $id;
    private $name;
    private $email;
    private $password;

    public function __construct()
    {
        $this->conn = new Database();
    }

    public static function create(array $data): ?User
    {
        $instance = new self();
        $instance->conn->execute("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)", [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);

        if (!$instance->conn->lastInsertId()) {
            return null;
        }

        $instance->id = $instance->conn->lastInsertId();

        $instance->setProperties($data);

        return $instance;
    }


    public static function update(int $id, array $data): ?User
    {
        $instance = new self();

        $instance = self::getById($id);

        if (!$instance) {
            return null;
        }

        $instance->conn->execute("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id", [
            'id' => $id,
            'name' => $data['name'],
        ]);

        // check if update is successful

        $instance->setProperties($data);

        return $instance;
    }

    public static function getByEmail(string $email): ?User
    {
        $instance = new self();
        $result = $instance->conn->fetchOne("SELECT * FROM users WHERE email = :email", [
            'email' => $email,
        ]);

        if (!$result) {
            return null;
        }

        $instance = new self();
        $instance->setProperties($result);

        return $instance;
    }

    public static function getById(int $id): ?User
    {
        $instance = new self();
        $result = $instance->conn->fetchOne("SELECT * FROM users WHERE id = :id", [
            'id' => $id,
        ]);

        if (!$result) {
            return null;
        }

        $instance = new self();
        $instance->setProperties($result);

        return $instance;
    }

    public static function delete(int $id, bool $soft = false): bool
    {
        if ($soft) {
            $instance = new self();
            $result = $instance->conn->execute("UPDATE users SET deleted_at = NOW() WHERE id = :id", [
                'id' => $id,
            ]);
        } else {
            $instance = new self();
            $result = $instance->conn->execute("DELETE FROM users WHERE id = :id", [
                'id' => $id,
            ]);
        }

        if (!$result) {
            return false;
        }

        return true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    private function setProperties(array $result): void
    {
        $this->id = $result['id'];
        $this->name = $result['name'];
        $this->email = $result['email'];
        $this->password = $result['password'];
    }
}