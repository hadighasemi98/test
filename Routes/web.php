<?php

use App\Core\Router\Route;


Route::get("/test", function (){
        echo "123123";
});

Route::get("/article","ArticleController@index" )  ; 






