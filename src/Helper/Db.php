<?php

declare(strict_types=1);

namespace Faster\Helper;

use Faster\Db\Database;

/**
 * Db
 * -----------
 * Db
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Helper
 */
class Db
{
    const DSN = 'dsn';
    const TABLE_PREFIX = 'table_prefix';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const PDO_OPTIONS = 'pdo_options';

    private static array $options;
    private static string $defaultConnection;
    private static array $db = [];

    /**
     * init
     *
     * @param  array $options
     * @param  string $defaultConnection
     * @return void
     */
    public static function init(array $options, string $defaultConnection)
    {
        static::validateDbOptions($options);
        if (array_key_exists($defaultConnection, $options)) {
            static::$defaultConnection = $defaultConnection;
        }else{
            throw new \Exception("Default connection '$defaultConnection' is not exist in options");
        }
    }
    /**
     * validateDbOptions
     *
     * @param  array $options
     * @return void
     */
    private static function validateDbOptions(array $options)
    {
        static::$options = [];
        foreach ($options as $key => $config) {
            $dsn = $config[static::DSN] ?? '';
            $tbl_prefix = $config[static::TABLE_PREFIX] ?? '';
            $username = $config[static::USERNAME] ?? '';
            $password = $config[static::PASSWORD] ?? '';
            $pdo_options = $config[static::PDO_OPTIONS] ?? [];
            if($dsn && is_string($key)){
                static::$options[$key] = [
                    static::DSN => $dsn,
                    static::TABLE_PREFIX => $tbl_prefix,
                    static::USERNAME => $username,
                    static::PASSWORD => $password,
                    static::PDO_OPTIONS => $pdo_options
                ];
            }else{
                throw new \Exception('Db options is not configured correctly.');
            }
        }
    }    
    /**
     * defaultConnection
     *
     * @return string
     */
    public static function defaultConnection(): string
    {
        return static::$defaultConnection;
    }    
    /**
     * get
     *
     * @param  string $connection
     * @return Database
     */
    public static function get(string $connection): Database
    {
        if(!array_key_exists($connection, static::$db)){
            $config = static::$options[$connection];
            $dsn = $config[static::DSN] ?? '';
            $tbl_prefix = $config[static::TABLE_PREFIX] ?? null;
            $username = $config[static::USERNAME] ?? null;
            $password = $config[static::PASSWORD] ?? null;
            $pdo_options = $config[static::PDO_OPTIONS] ?? null;
            $config = [$connection, $dsn, $username, $password, $tbl_prefix, $pdo_options];
            static::$db[$connection] = make(Database::class, $config);
        }
        
        return static::$db[$connection];
    }
}
