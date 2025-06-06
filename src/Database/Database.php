<?php

namespace App\Database;

class Database
{
    private $conn;

    public function __construct()
    {
        $dbPath = __DIR__ . '/../../products.sqlite';
        if (!file_exists($dbPath)) {
            touch($dbPath);
        }

        $this->conn = new \PDO('sqlite:' . $dbPath);
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query(string $query, array $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $query, array $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchOne(string $query, array $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function execute(string $query, array $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function lastInsertId(): int
    {
        return $this->conn->lastInsertId();
    }
}