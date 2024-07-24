<?php

declare(strict_types=1);

namespace Faster\Http\Auth;

/**
 * AuthModelInterface
 * -----------
 * Abstraction for AuthModel
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Auth
 */
interface AuthModelInterface
{
    /**
     * getUser
     *
     * @param int|string $userId
     * @return array
     */
    public function getUser($userId): array;
    /**
     * validateHash
     *
     * @param  array $userData
     * @param  string $userHash
     * @return bool
     */
    public function validateHash(array $userData, string $userHash): bool;     
    /**
     * generateHash
     *
     * @param  mixed $userData
     * @return string
     */
    public function generateHash(array $userData): string;   
    /**
     * getRoles
     *
     * @param  array $userData
     * @param  string $roleAttribute
     * @return array
     */
    public function getRoles(array $userData, string $roleAttribute = 'role'): array;
}
