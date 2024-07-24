<?php

declare(strict_types=1);

namespace Faster\Helper;

use Faster\Container\BasicContainer;

/**
 * Container
 * -----------
 * Container class for DI
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Helper
 */
class Container
{
    private static ?BasicContainer $container = null;

    /**
     * get
     *
     * @param  string $id
     * @param  ?array $options
     * @return void
     */
    public static function get(string $id, ?array $params = null, bool $shared = false)
    {
        if (static::$container === null) {
            static::$container = new BasicContainer();
        }
        if ($shared) {
            return static::$container->getShared($id, $params);
        } else {
            return static::$container->get($id, $params);
        }
    }
}
