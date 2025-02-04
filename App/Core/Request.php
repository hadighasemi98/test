<?php
namespace App\Core;

use App\Middlewares\GlobalMiddleware;
use hisorange\BrowserDetect\Parser as Browser;


class Request {

    private $agent ;
    private $ip ;
    private $uri ;
    private $params ;
    private $route_params ;
    private $method ;
 
    public function __construct()
    {
        $this->agent  = $_SERVER['HTTP_USER_AGENT'] ;
        $this->ip     = $_SERVER['SERVER_ADDR'];
        $this->uri    = strtok(string: $_SERVER['REQUEST_URI'] , token: '?') ;
        $this->params = $_REQUEST ;
        $this->method = strtolower($_SERVER['REQUEST_METHOD']) ;
    }

    public function __get(string $param): mixed
    {
        if (array_key_exists($param, $this->params)) {
            return $this->params[$param];
        }
    }

    public function getAgent(): mixed
    {
        return $this->agent ;
    }

    public function getIp (): string
    {
        return $this->ip ;
    }

    public function getParams(): array
    {
        return $this->params ;
    }

    # Create SEO friendly routes
    public function addRouteParam($key , $value): mixed
    {
        return $this->route_params[$key] = $value ;
    }

    public function getRouteParam($key): mixed
    {
        return $this->route_params[$key] ;
    }
    
    public function getUri(): bool|string
    {
        return $this->uri ;
    }

    public function getMethod(): string
    {
        return $this->method ;
    }
}