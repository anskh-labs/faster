<?php

declare(strict_types=1);

namespace Faster\Http\Auth;

/**
 * AuthProviderInterface
 * -----------
 * AuthProvider contract
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Auth
 */
interface AuthProviderInterface
{    
    /**
     * getProvider
     *
     * @return string
     */
    public function getProvider(): string;    
    /**
     * getLoginUri
     *
     * @return string
     */
    public function getLoginUri(): string;    
    /**
     * getLogoutUri
     *
     * @return string
     */
    public function getLogoutUri(): string;    
    /**
     * getRoles
     *
     * @return array
     */
    public function getRoles(): array;    
    /**
     * getPermissions
     *
     * @return array
     */
    public function getPermissions(): array;
}