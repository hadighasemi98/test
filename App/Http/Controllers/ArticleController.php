<?php
namespace App\Http\Controllers;

class ArticleController
{
    public function index()
    {
        echo "ArticleController@index";
    }

    public static function search() {
        $pdo = Database::connect();
        $query = $_GET['q'] ?? '';

        if (!$query) {
            return json_encode(["error" => "Missing search query"]);
        }

        $stmt = $pdo->prepare("SELECT id, title, content, created_at FROM articles 
                                WHERE MATCH(title, content) AGAINST (:query IN NATURAL LANGUAGE MODE)");
        $stmt->execute(['query' => $query]);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(["articles" => $articles]);
    }
}