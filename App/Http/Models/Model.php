<?php
namespace App\Http\Models;

use App\Core\Database\DB;

abstract class Model
{
    public function __construct()
    {
        $pdo = DB::connect();
    }

    abstract public function table(): string;
    // ToDo: Implement base model
}