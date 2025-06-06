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

    public function createUser(string $name, string $email, string $password): void
    {
        $this->conn->execute("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)", [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
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