<?php

declare(strict_types=1);

namespace Faster\Helper;

use Faster\Container\ConfigContainer;
use Exception;
use Faster\Component\Enums\EnvironmentEnum;
use InvalidArgumentException;

/**
 * Config
 * -----------
 * Class for working with @see Faster\Config\Config
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Helper
 */
class Config
{
    private static ?ConfigContainer $container = null;
    /**
     * init
     *
     * @param  string $path
     * @param  string $environment
     * @return void
     * @throws \InvalidArgumentException
     */
    public static function init(string $path, string $environment = EnvironmentEnum::DEVELOPMENT): void
    {
        if (!$path && !is_dir($path)) {
            throw new InvalidArgumentException("Given path '{$path}'  is not valid.");
        }
        if (!EnvironmentEnum::hasValue($environment)) {
            throw new InvalidArgumentException("Available environment is " . implode(" or ", EnvironmentEnum::all()) . ", $environment is given.");
        }
        if (static::$container === null) {
            static::$container = new ConfigContainer($path, $environment);
        }
    }

    /**
     * get
     *
     * @param  mixed $offset
     * @param  mixed $defaultValue
     * @return mixed
     */
    public static function get($offset, $defaultValue = null)
    {
        if (static::$container === null) {
            throw new Exception('Init config by calling init method first.');
        }

        return static::has($offset) ? static::$container[$offset] : $defaultValue;
    }

    /**
     * set
     *
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public static function set($offset, $value): void
    {
        if (static::$container === null) {
            throw new Exception('Init config by calling init method first.');
        }

        static::$container[$offset] = $value;
    }

    /**
     * has
     *
     * @param  mixed $offset
     * @return bool
     */
    public static function has($offset): bool
    {
        if (static::$container === null) {
            throw new Exception('Init config by calling init method first.');
        }

        return static::$container->offsetExists($offset);
    }
    /**
     * path
     *
     * @return string
     */
    public static function path(): string
    {
        if (static::$container === null) {
            throw new Exception('Init config by calling init method first.');
        }

        return static::$container->getPath();
    }
    /**
     * environment
     *
     * @return string
     */
    public static function environment(): string
    {
        if (static::$container === null) {
            throw new Exception('Init config by calling init method first.');
        }

        return static::$container->getEnvironment();
    }
}
