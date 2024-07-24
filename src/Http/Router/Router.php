<?php

declare(strict_types=1);

namespace Faster\Http\Router;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Faster\Helper\Url;

use function FastRoute\simpleDispatcher;

/**
 * Router
 * -----------
 * Router
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Router
 */
class Router implements RouterInterface
{
    private array $routes = [];
            
    /**
     * __construct
     *
     * @param  mixed $routes
     * @return void
     */
    public function __construct(array $routes)
    {
        $this->validate($routes);
    }    
    /**
     * validate
     *
     * @param  array $routes
     * @return void
     * @throws \Exception
     */
    private function validate(array $routes)
    {
        foreach($routes as $name => $route){
            if(!is_string($name)){
                throw new \Exception("Route name must be string.");
            }elseif(array_key_exists($name, $this->routes)){
                throw new \Exception("Route with name '$name' is exist.");
            }
            list($method, $path, $handler) = $route;
            if(!is_string($method) && !is_array($method)){
                throw new \Exception("Http method must be string or array.");
            }
            if(!is_string($path)){
                throw new \Exception("Route path must be string.");                
            }
            if(!is_string($handler) && !is_array($handler) && !is_callable($handler)){
                throw new \Exception("Route handler must be string, array, or callable.");
            }
            $this->routes[$name] = [$method, $path, $handler];
        }
    }
    /**
     * @inheritdoc
     */
    public function getDispatcher(): Dispatcher
    {
        $basePath = Url::getBasePath();
        $routes = array_values($this->routes);
        return simpleDispatcher(function (RouteCollector $r) use($routes, $basePath) {
            foreach ($routes as $route) {
                list($method, $path, $handler) = $route;
                $path = $basePath . $path;
                $r->addRoute($method, $path, $handler);
            }
        });
    }    
    /**
     * @inheritdoc
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
    /**
     * @inheritdoc
     */
    public function getRoute(string $name): array
    {
        return $this->routes[$name] ?? [];
    }
    /**
     * @inheritdoc
     */
    public function routeExists(string $name): bool
    {
        return array_key_exists($name, $this->routes);
    }
    /**
     * @inheritdoc
     */
    public function addRoute(string $name, array $route)
    {
        if($this->routeExists($name)){
            throw new \Exception("Route '$name' is already exists.");
        }
        $this->routes[$name] = $route;
    }
}
