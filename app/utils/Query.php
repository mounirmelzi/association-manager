<?php

namespace App\Utils;

use App\Core\Database;

class Query
{
    private Database $database;
    private ?string $table = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function getAll(int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT * FROM $this->table";
        if (($limit > 0) && ($offset >= 0)) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $response = $this->query($sql);
        if ($response["success"]) {
            return $response["data"];
        } else {
            return [];
        }
    }

    public function getById(int $id): mixed
    {
        $response = $this->query("SELECT * FROM $this->table WHERE id = :id", ["id" => $id]);
        if ($response["success"]) {
            return $response["data"][0] ?? null;
        } else {
            return null;
        }
    }

    public function insert(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $response = $this->query("INSERT INTO $this->table ($columns) VALUES ($values)", $data);
        return $response["success"];
    }

    public function update(array $data): bool
    {
        $id = $data['id'];
        unset($data['id']);

        $updates = array_map(fn($key) => "$key = :$key", array_keys($data));
        $updates = implode(', ', $updates);

        $data['id'] = $id;

        $response = $this->query("UPDATE $this->table SET $updates WHERE id = :id", $data);
        return $response["success"];
    }

    public function delete(int $id): bool
    {
        $response = $this->query("DELETE FROM $this->table WHERE id = :id", ["id" => $id]);
        return $response["success"];
    }

    public function truncate(): bool
    {
        $response = $this->query("DELETE FROM $this->table");
        return $response["success"];
    }

    private function query(string $sql, array $data = []): array
    {
        if ($this->table === null) {
            return ["success" => false, "data" => []];
        }

        $connection = $this->database->getConnection();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($data);
        return ["success" => $success, "data" => $stmt->fetchAll()];
    }
}
