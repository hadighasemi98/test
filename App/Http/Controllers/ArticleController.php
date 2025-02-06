<?php
namespace App\Http\Controllers;

use App\Core\Database\DB;
use App\Core\Request;
use ArticleService;
use PDO;

class ArticleController
{
    public function __construct(private ArticleService $articleService)
    {

    }

    public function index()
    {
        echo "ArticleController@index";
    }

    public function search(Request $request) 
    {
        return $this->articleService->search($request->getRouteParam('title'));
    }
}