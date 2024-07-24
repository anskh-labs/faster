<?php

declare(strict_types=1);

namespace Faster\Http\Auth;

/**
 * AnonymousIdentity
 * -----------
 * Identitiy for guest or not athenticated user
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Auth
 */
class UserIdentity implements UserIdentityInterface
{
    /** @var string|int|null */
    protected $id;
    protected array $roles;
    protected array $permissions;
    protected array $data;

    /**
     * __construct
     *
     * @param  string|int|null $id
     * @param  array $roles
     * @param  array $permissions
     * @param  array $data
     * @return void
     */
    public function __construct($id = null, array $roles = [], array $permissions = [], array $data = [])
    {
        $this->id = $id;
        $this->roles = $roles;
        $this->permissions = $permissions;
        $this->data = $data;
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
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
    /**
     * @inheritdoc
     */
    public function isAuthenticated(): bool
    {
        return empty($this->id) === false;
    }
    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        return $this->data;
    }
}
