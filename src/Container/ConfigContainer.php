<?php

declare(strict_types=1);

namespace Faster\Container;

use ArrayAccess;

/**
 * Configuration container
 * -----------
 * Configuration container for accessing 
 * app config folder with support dot notation
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Container
 */
class ConfigContainer implements ArrayAccess
{
    private array $container = [];
    private string $path;
    private string $env;

    /**
     * __construct
     *
     * @param  string $path
     * @param  string $environment
     * @return void
     */
    public function __construct(string $path, string $environment)
    {
        $this->path = $path;
        $this->env = $environment;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset): bool
    {
        if (isset($this->container[$offset])) {
            return true;
        }

        $name = strtok($offset, '.');
        if (isset($this->container[$name])) {
            $p = $this->container[$name];
            while (false !== ($name = strtok('.'))) {
                if (!isset($p[$name])) {
                    return false;
                }

                $p = $p[$name];
            }
            $this->container[$offset] = $p;

            return true;
        } else {
            $file = "{$this->path}/{$name}.php";
            if (is_file($file) && is_readable($file)) {
                $this->container[$name] = include $file;
                if ($this->env) {
                    $file = "{$this->path}/{$this->env}/{$name}.php";
                    if (is_file($file) && is_readable($file)) {
                        $this->container[$name] = array_replace_recursive($this->container[$name], include $file);
                    }
                }
                return $this->offsetExists($offset);
            } else {
                $file = "{$this->path}/{$this->env}/{$name}.php";
                if (is_file($file) && is_readable($file)) {
                    $this->container[$name] = include $file;
                    return $this->offsetExists($offset);
                }
            }

            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->container[$offset] : null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }    
    /**
     * getPath
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
    /**
     * getEnvironment
     *
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->env;
    }
}
