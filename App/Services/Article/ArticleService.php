<?php

use App\Core\Database\DB;
use App\Http\Models\Article;

class ArticleService 
{
    public function search(string $query) 
    {
        if (!$query) {
            return json_encode(["error" => "Missing search query"]);
        }

        $articles = (new Article())->db->search(column: 'title',query: $query); ;

        return json_encode(["articles" => $articles]);
    }
}