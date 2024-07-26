<?php

declare(strict_types=1);

namespace Faster\Helper;

use Faster\Http\Application;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * App
 * -----------
 * App
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Helper
 */
class App
{        
    private static string $configPath;
    /**
     * make
     *
     * @param  string $configPath
     * @return Application
     */
    public static function make(string $configPath): Application
    {
        static::$configPath = $configPath;
        return Application::getInstance($configPath);
    }
    /**
     * environment
     *
     * @return string
     */
    public static function environment() : string
    {
        return config('app.env');
    }    
    /**
     * debug
     *
     * @return bool
     */
    public static function debug() : bool
    {
        return (bool)config('app.debug');
    }    
    /**
     * name
     *
     * @return bool
     */
    public static function name() : bool
    {
        return config('app.name');
    }    
    /**
     * version
     *
     * @return string
     */
    public static function version() : string
    {
        return config('app.version');
    }        
    /**
     * request
     *
     * @return ServerRequestInterface
     */
    public static function request() : ServerRequestInterface
    {
        return Application::getInstance(static::$configPath)->getRequest();
    }    
    /**
     * response
     *
     * @return ResponseInterface
     */
    public static function response() : ResponseInterface
    {
        return Application::getInstance(static::$configPath)->getResponse();
    }
}