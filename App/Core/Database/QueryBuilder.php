<?php

namespace App\Core\Database;

class QueryBuilder {

    protected $table ;
    protected $attributes = [] ;
    protected $primary_key ;

    public function __construct(protected $connection){}

    public function create(array $data): bool
    {
        $this->connection->insert($this->table,$data);
        return true;
    }

    public function find(int $id, string|array $columns): object
    {
        $sql = $this->connection->get($this->table, $columns ?? '*', [$this->primary_key => $id]);

        foreach ($sql as $col => $val) {
            $this->attributes[$col] = $val;
        }
        return $this ;
    }

    public function get(array $columns , array $where): array
    {
        return $this->connection->select($this->table, $columns, $where) ;
    }

    public function all(): array
    {
        return $this->connection->select($this->table, '*') ;
    }

    public function update(array $data , array $where): int
    {
        $sql = $this->connection->update($this->table, $data, $where);
        return $sql->rowCount() ;
    }
    
    public function delete(array $where): int
    {
        $sql = $this->connection->delete($this->table, $where);
        return $sql->rowCount() ;
    }
}