<?php
namespace App\Http\Models;

class Article extends Model
{
    public function __construct()
    {
        echo "Article model";
    }

    public function table(): string
    {
        return "articles";
    }
}