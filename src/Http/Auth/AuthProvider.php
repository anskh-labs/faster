<?php

declare(strict_types=1);

namespace Faster\Http\Auth;

/**
 * AuthProvider
 * -----------
 * AuthProvider 
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Auth
 */
class AuthProvider implements AuthProviderInterface
{
    private string $loginUri;
    private string $logoutUri;
    private array $roles;
    private array $permissions;

    /**
     * __construct
     *
     * @param  string $loginUri
     * @param  string $logoutUri
     * @param  array $roles
     * @param  array $rolePermissions
     * @return void
     */
    public function __construct(string $loginUri = '', string $logoutUri = '', array $roles = [], array $permissions = [])
    {
        $this->loginUri = $loginUri;
        $this->logoutUri = $logoutUri;
        $this->roles = $roles;
        $this->permissions = $permissions;
    }
    /**
     * @inheritdoc
     */
    public function getProvider(): string
    {
        return 'User Authentication';
    }
    /**
     * @inheritdoc
     */
    public function getLoginUri(): string
    {
        return $this->loginUri;
    }
    /**
     * @inheritdoc
     */
    public function getLogoutUri(): string
    {
        return $this->logoutUri;
    }
    /**
     * @inheritdoc
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
    /**
     * @inheritdoc
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
