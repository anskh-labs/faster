<?php

declare(strict_types=1);

namespace Faster\Helper;

use Faster\Http\Renderer\RendererInterface;
use Faster\Http\Renderer\Renderer;

/**
 * View
 * -----------
 * View
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Helper
 */
class View
{
    private static ?RendererInterface $renderer = null;

    /**
     * init
     *
     * @param  array $viewPath
     * @param  string $fileExtension
     * @return void
     */
    public static function init(string $viewPath, string $fileExtension = '.phtml')
    {
        static::$renderer = make(Renderer::class, [$viewPath, $fileExtension]);
    }    
    /**
     * renderer
     *
     * @return RendererInterface
     */
    public static function renderer(): RendererInterface
    {
        if(static::$renderer === null){
            throw new \Exception('Create view renderer first by call View::init.');      
        }
        return static::$renderer;
    }
}