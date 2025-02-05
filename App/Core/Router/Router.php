<?php
namespace App\Core\Router;

use App\Core\Request;
use Exception;

class Router 
{
    private $request ;
    private $routes ;
    private $currentRoute ;

    public function __construct()
    { 
        global $request;
        $this->request = $request;
        $this->routes = Route::routes() ;
        $this->currentRoute = $this->findRoute($this->request);
        // var_dump( $this->currentRoute);

        # Middlewares
        $this->runMiddleware() ;
    }

    private function runMiddleware(): void
    {
        $middleware = $this->currentRoute['middleware'] ?? null ;

        if (!is_null($middleware)) {
            foreach ($middleware as $middlewareClass) {
                $class = new $middlewareClass ;
                $class->handle() ;
            }
        }
    }
    
    public function findRoute(Request $request): array|bool|null
    {
        foreach($this->routes as $route){

            if ( !in_array( needle: $request->getMethod() , haystack: $route['method'] ) )
            {
                return false ;
            }

            if ( $this->regexMatched($route))
            {
                return $route ;
            }
        }

        return null;
    }

    public function regexMatched($route)
    {
        $routePattern = " /^".str_replace(["/","{","}"],["\/","(?<",">[-%\w+]+)"],$route['uri'])."$/ ";
        $uri = $this->request->getUri();
        $result = preg_match($routePattern , $uri ,$matches) ;

        if(!$result){
            return false ;
        }

        foreach($matches as $key => $value)
        {
            if (!is_int($key)) {
                $this->request->addRouteParam($key , $value); 
            }
        }

        return true ;
     }

    public function invalidMethod($request)
    { 
        foreach($this->routes as $route){
            
            if(!in_array( $request->getMethod() , $route['method'] ) && 
               $request->getUri() == $route['uri']
            )
            {
                return $this->dispatch405() ;
            }
        }
    }

    public function invalidUri($uri)
    { 
        foreach($this->routes as $route){

            if( ($uri->getUri() == $this->regexMatched($route)) )
            {
                return $route ;
            }
        }
        return $this->dispatch404() ;
    }


    public function dispatch404()
    { 
        header("HTTP/1.1 404 Not Found");
        //ToDo
        // view("errors/404");
        die();
    }

    public function dispatch405()
    { 
        header("HTTP/1.0 405 Method Not Allowed");
        //ToDo
        // view("errors/405");
        die();
    }

    public function dispatch($route)
    {
        $action = $route['action'] ?? null;
        if(is_null($action) and empty($action)){
            return;
        }

        #closure
        if(is_callable($action)){
            $action();
        }

        # Controller@index
        if(is_string($action)){
            $action = explode("@",$action);
        }

        # ['Controller','index']
        if(is_array($action)){

            $className = "App\\Http\\Controllers\\" . $action[0];
            if(!class_exists($className)){
                throw new Exception("Class not exist $className");
            }
                
            $controller = new $className();
            
            $methodName = $action[1];
            if(!method_exists($controller ,$methodName))
                throw new Exception("Method not exist $methodName");

            $controller->{$methodName}();
        }
    }

    public function run()
    {
        $this->invalidUri($this->request);
        $this->invalidMethod($this->request);
        $this->dispatch($this->currentRoute);
    }
}