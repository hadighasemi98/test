<?php

namespace App\Core\Database;

use PDO;
use PDOException;

class QueryBuilder {

    protected string $table;
    protected array $attributes = [];
    protected string $primaryKey = 'id';

    public function __construct(protected PDO $connection) {}

    public function table(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function create(array $data): ?int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($col) => ":$col", array_keys($data)));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute() ? (int) $this->connection->lastInsertId() : null;
    }

    public function find(int $id, array|string $columns = '*'): ?array
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function get(array|string $columns = '*'): array
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $sql = "SELECT {$columns} FROM {$this->table}";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(array $data, array $where): int
    {
        $setPart = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));
        $wherePart = $this->buildWhereClause($where);

        $sql = "UPDATE {$this->table} SET {$setPart} {$wherePart}";
        $stmt = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $this->bindWhereValues($stmt, $where);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(array $where): int
    {
        $wherePart = $this->buildWhereClause($where);
        $sql = "DELETE FROM {$this->table} {$wherePart}";

        $stmt = $this->connection->prepare($sql);
        $this->bindWhereValues($stmt, $where);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function search(string $column, string $query): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} LIKE :query";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buildWhereClause(array $where): string
    {
        if (empty($where)) {
            return '';
        }

        $conditions = implode(' AND ', array_map(fn($col) => "$col = :$col", array_keys($where)));
        return " WHERE {$conditions}";
    }

    private function bindWhereValues(\PDOStatement $stmt, array $where): void
    {
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
    }
}
