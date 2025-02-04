<?php

class QueryBuilder
{
    private string $table;

    public function __construct(protected $pdo)
    {
    }

    public function selectAll()
    {
        $query = $this->pdo->prepare("select * from {$this->table}");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function find(int $id): object
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id={$id}");
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function where(string $key, $value)
    {
        $query = $this->pdo->prepare("select * from {$this->table} where $key= '$value'");
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function table(string $table): static
    {
        $this->table = $table;
        return $this;
    }
}