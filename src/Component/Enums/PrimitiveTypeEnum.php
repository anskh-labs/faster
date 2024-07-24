<?php

declare(strict_types=1);

namespace Faster\Component\Enums;

/**
 * PrimitiveTypeEnum
 * -----------
 * used in Model
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Component\Enums
 */
class PrimitiveTypeEnum extends BaseEnum
{
    public const BOOL = 'boolean';
    public const INT = 'integer';
    public const FLOAT = 'float';
    public const STRING = 'string';
    public const ARRAY = 'array';
    public const OBJECT = 'object';
    public const RAW = 'raw';
}

