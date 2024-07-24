<?php

declare(strict_types=1);

namespace Faster\Model;

use Faster\Db\Database;
use Faster\Html\Pagination;
use Exception;
use PDO;

/**
 * DbViewModel
 * -----------
 * DbViewModel
 *
 * @author Khaerul Anas <khaerulanas@live.com>
 * @since 1.0.0
 * @package Faster\Model
 */
abstract class DbViewModel extends Model
{
    protected Database $db;
    protected string $table;

    /**
     * __construct
     *
     * @param  mixed $db
     * @return void
     */
    public function __construct(?Database $db = null)
    {
        $this->db = $db ?? db();
    }
    /**
     * getTable
     *
     * @return string
     */
    public function getTable(): string
    {
        if (!$this->table) {
            throw new Exception('This method must be implemented.');
        }
        return $this->table;
    }
    /**
     * getRecordCount
     *
     * @param  array|string|null $where
     * @return int
     */
    public function getRecordCount($where = null): int
    {
        return $this->db->getRecordCount($this->table, $where);
    }
    /**
     * isExists
     *
     * @param  array|string|null  $where
     * @return bool
     */
    public function isExists($where = null): bool
    {
        return $this->db->recordExists($this->table, $where);
    }
    /**
     * table
     *
     * @return string
     */
    public static function table(): string
    {
        throw new Exception('This method must be implemented.');
    }    
    /**
     * column
     *
     * @return string
     */
    public static function column(): string
    {
        throw new Exception('This method must be implemented.');
    }
    /**
     * Get db
     *
     * @param  ?Database $db
     * @return Database
     */
    public static function db(?Database $db = null): Database
    {
        return $db ?? db();
    }
    /**
     * all
     *
     * @param  string $column
     * @param  int $limit
     * @param  int $offset
     * @param  ?string $orderby
     * @param  ?Database $db
     * @return array
     */
    public static function all(string $column = '*', int $limit = 0, int $offset = -1, ?string $orderby = null, ?Database $db = null): array
    {
        if($column === '*'){
            $column = static::column();
        }
        return static::db($db)->select(static::table(), $column, null, $limit, $offset, $orderby);
    }
    /**
     * allColumn
     *
     * @param  string $column
     * @param  int $limit
     * @param  int $offset
     * @param  ?string $orderby
     * @param  ?Database $db
     * @return array
     */
    public static function allColumn(string $column, int $limit = 0, int $offset = -1, ?string $orderby = null, ?Database $db = null): array
    {
        return static::db($db)->select(static::table(), $column, null, $limit, $offset, $orderby, PDO::FETCH_COLUMN);
    }
    /**
     * row
     *
     * @param  string $column
     * @param  array|string|null $where
     * @param  ?Database $db
     * @return array
     */
    public static function row(string $column = '*', $where = null, ?Database $db = null): array
    {
        if($column === '*'){
            $column = static::column();
        }
        return static::db($db)->getRow(static::table(), $column, $where);
    }
    /**
     * recordCount
     *
     * @param  array|string|null  $where
     * @param  ?Database $db
     * @return int
     */
    public static function recordCount($where = null, ?Database $db = null): int
    {
        return static::db($db)->getRecordCount(static::table(), $where);
    }
    /**
     * find
     *
     * @param  array|string|null  $where
     * @param  string $column
     * @param  int $limit
     * @param  ?string $orderby
     * @param  ?Database $db
     * @return array
     */
    public static function find($where = null, string $column = '*', int $limit = 0, int $offset = -1, ?string $orderby = null, ?Database $db = null): array
    {
        if($column === '*'){
            $column = static::column();
        }
        return static::db($db)->select(static::table(), $column, $where, $limit, $offset, $orderby);
    }
    /**
     * paginate
     *
     * @param  array|string|null  $where
     * @param  string $column
     * @param  ?int $perpage
     * @param  ?string $orderby
     * @param  ?Database $db
     * @return DbRecordSet
     */
    public static function paginate($where = null, string $column = '*', ?int $perpage = null, ?string $orderby = null, ?Database $db=null): DbRecordSet
    {
        $pager = new Pagination(static::recordCount($where), $perpage);
        if($column === '*'){
            $column = static::column();
        }
        $rows = static::db($db)->select(static::table(), $column, $where, $pager->perPage(), $pager->offset(), $orderby);
        return new DbRecordSet($rows, $pager);
    }
    /**
     * findColumn
     *
     * @param  array|string|null $where
     * @param  string $column
     * @param  int $limit
     * @param  int $offset
     * @param  ?string $orderby
     * @param  ?Database $db
     * @return array
     */
    public static function findColumn($where = null, string $column = '*', int $limit = 0, int $offset = -1, ?string $orderby = null, ?Database $db = null): array
    {
        if($column === '*'){
            $column = static::column();
        }
        return static::db($db)->select(static::table(), $column, $where, $limit, $offset, $orderby, PDO::FETCH_COLUMN);
    }
    /**
     * exists
     *
     * @param  array|string|null  $where
     * @param  ?Database $db
     * @return bool
     */
    public static function exists($where = null, ?Database $db = null): bool
    {
        return static::db($db)->recordExists(static::table(), $where);
    }
}
