<?php declare(strict_types=1);

namespace Faster\Exception;

use Exception;

/**
 * CsrfFailure
 * -----------
 * Class to define CsrfFailure exception
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Exception
 */
class CsrfFailure extends Exception
{
    protected string $route;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct("Failed CSRF check!");
    }
}
