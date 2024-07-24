<?php

declare(strict_types=1);

namespace Faster\Container;

/**
 * Basic container
 * -----------
 * Basic container for get instance of class
 * based on full qualified name
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Container
 */
class BasicContainer
{
    private array $container = [];

    /**
     * get
     *
     * @param  string $id
     * @param  ?array $params
     * @return void
     */
    public function get(string $id, ?array $params = null)
    {
        if ($this->has($id)) {
            if (isset($this->container[$id])) {
                unset($this->container[$id]);
            }
            if ($params) {
                $obj = new $id(...$params);
            } else {
                $obj = new $id();
            }

            return $obj;
        }

        throw new \Exception("Class {$id} doesn't exists.");
    }
    /**
     * getShared
     *
     * @param  string $id
     * @param  ?array $params
     * @return void
     */
    public function getShared(string $id, ?array $params = null)
    {
        if ($this->has($id)) {
            if (!isset($this->container[$id])) {
                if ($params) {
                    $obj = new $id(...$params);
                } else {
                    $obj = new $id();
                }
                $this->container[$id] = $obj;
            }

            return $this->container[$id];
        }

        throw new \Exception("Class {$id} doesn't exists.");
    }
    /**
     * has
     *
     * @param  string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return class_exists($id);
    }
}
