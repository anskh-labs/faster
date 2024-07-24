<?php

declare(strict_types=1);

namespace Faster\Http\Auth;

use Faster\Db\Database;
use Faster\Helper\Client;

/**
 * AuthModel
 * -----------
 * AuthModel
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Http\Auth
 */
class AuthModel implements AuthModelInterface
{    
    private string $table;
    private ?Database $db;
    private string $userIdAttribute;
    private string $uniqueAttribute;
    private string $selectColumns;

    /**
     * __construct
     *
     * @param  \Faster\Db\Database $db
     * @param  string $userTable
     * @param  string $userIdAttribute
     * @param  string $uniqueAttribute
     * @param  string $selectColumns
     * @return void
     */
    public function __construct(Database $db, string $userTable = 'user', string $userIdAttribute = 'id', string $uniqueAttribute = 'email', string $selectColumns = '*')
    {
        $this->db = $db;
        $this->table = $db->getTable($userTable);
        $this->userIdAttribute = $userIdAttribute;
        $this->uniqueAttribute = $uniqueAttribute;
        $this->selectColumns = $selectColumns;
    }
    /**
     * @inheritdoc
     */
    public function getUser($userId): array
    {
        return $this->db->getRow($this->table, $this->selectColumns, [$this->userIdAttribute . '=' => $userId]);
    }    
    /**
     * @inheritdoc
     */
    public function validateHash(array $userData, string $userHash): bool
    {
        $hash = $this->generateHash($userData);
        return hash_equals($hash, $userHash);
    } 
    /**
     * @inheritdoc
     */   
    public function generateHash(array $userData): string
    {
        $userAgent = Client::getUserAgent();
        return sha1($userData[$this->uniqueAttribute] . $userAgent);
    }
    /**
     * @inheritdoc
     */
    public function getRoles(array $userData, string $roleAttribute = 'role'): array
    {
        return explode(',' , $userData[$roleAttribute]);
    }
}
