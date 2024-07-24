<?php

declare(strict_types=1);

namespace Faster\Helper;

use Faster\Http\Router\Router as SimpleRouter;
use Faster\Http\Router\RouterInterface;

/**
 * Router
 * -----------
 * Router
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Helper
 */
class Router
{    
    private static ?RouterInterface $router = null;

    /**
     * make
     *
     * @param  array $routes
     * @return RouterInterface
     */
    public static function make(array $routes): RouterInterface
    {
        static::$router = new SimpleRouter($routes);
        return static::$router;
    }
    /**
     * get route definition based on route name
     *
     * @return array
     */
    public static function get(string $name): array
    {
        if(static::$router === null){
            throw new \Exception('Create router first by call Router::make first.');
        }
        
        return static::$router->getRoute($name);
    }    
    /**
     * exists
     *
     * @param  string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        if(static::$router === null){
            throw new \Exception('Create router first by call Router::make first.');
        }

        return static::$router->routeExists($name);
    }
}
