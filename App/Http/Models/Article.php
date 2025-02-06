<?php
namespace App\Http\Models;

class Article extends Model
{
    public function __construct()
    {
        echo "Article model";
    }

    public function table(): void
    {
        $this->db->table('articles');
    }

}