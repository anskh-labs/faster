<?php declare(strict_types=1);

namespace Faster\Exception;

use Exception;

/**
 * MethodNotAllowed
 * -----------
 * Class to define MethodNotAllowed exception
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Exception
 */
class MethodNotAllowed extends Exception
{
    protected string $method;
    
    /**
     * __construct
     *
     * @param  string $method
     * @return void
     */
    public function __construct(string $method)
    {
        $this->method = $method;
        parent::__construct("Method '" . $method . "' is not allowed!");
    }
    
    /**
     * getMethod
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
