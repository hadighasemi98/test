<?php
namespace App\Http\Models;

use App\Core\Database\DB;
use App\Core\Database\QueryBuilder;

abstract class Model
{
    // ToDo: Implement base model

    public QueryBuilder $db ;
    public function __construct()
    {
        $this->db = new QueryBuilder(connection: DB::connect());
    }

    abstract public function table(): void;
}