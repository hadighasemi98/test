<?php
namespace App\Core\Router;

use Exception;

class Route 
{

    private static $routes = [];
    private const HTTP_VERB = ["get","post","put","patch","delete","options"];
    
    public static function add($method , $uri ,$action = null ,$middleware = [])
    {
        $method = is_array($method) ? $method  : [$method];
        self::$routes[] = 
        [
            "method"     => $method , 
            "uri"        => $uri , 
            "action"     => $action, 
            "middleware" => $middleware 
        ] ;
    }

    public static function __callStatic($name , $argument)
    {
        if ( !in_array($name , self::HTTP_VERB) ){
            throw new Exception ("Http verb not supported") ;
        }

        $uri        = $argument[0] ?? null;
        $action     = $argument[1] ?? null;
        $middleware = $argument[2] ?? null;
        
        self::add(method: $name ,uri: $uri , action: $action , middleware: $middleware);
    }

    public static function routes()
    {
        return self::$routes;
    }

}