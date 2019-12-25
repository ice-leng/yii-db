<?php

namespace Lengbin\YiiDb;

use Lengbin\YiiDb\Exception\NotSupportedException;
use Lengbin\YiiDb\Query\BaseQuery;

class Connection
{
    private $_schema;
    private $_driverName;
    private $_query;
    private $_quotedColumnNames;
    private $_quotedTableNames;
    public $tablePrefix = '';

    public $schemaCacheDuration = 3600;


    public function __construct($query, $driverName = 'mysql', $tablePrefix = '', $schemaCacheDuration = 3600)
    {
        $this->_query = $query;
        $this->_driverName = $driverName;
        $this->tablePrefix = $tablePrefix;
        $this->schemaCacheDuration = $schemaCacheDuration;
    }

    /**
     * @return BaseQuery
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * @var array mapping between PDO driver names and [[Schema]] classes.
     * The keys of the array are PDO driver names while the values are either the corresponding
     * schema class names or configurations. Please refer to [[Yii::createObject()]] for
     * details on how to specify a configuration.
     *
     * This property is mainly used by [[getSchema()]] when fetching the database schema information.
     * You normally do not need to set this property unless you want to use your own
     * [[Schema]] class to support DBMS that is not supported by Yii.
     */
    public $schemaMap = [
        'pgsql'   => 'Lengbin\YiiDb\Pgsql\Schema', // PostgreSQL
        'mysqli'  => 'Lengbin\YiiDb\Mysql\Schema', // MySQL
        'mysql'   => 'Lengbin\YiiDb\Mysql\Schema', // MySQL
        'sqlite'  => 'Lengbin\YiiDb\sqlite\Schema', // sqlite 3
        'sqlite2' => 'Lengbin\YiiDb\Sqlite\Schema', // sqlite 2
        'sqlsrv'  => 'Lengbin\YiiDb\Mssql\Schema', // newer MSSQL driver on MS Windows hosts
        'oci'     => 'Lengbin\YiiDb\Oci\Schema', // Oracle driver
        'mssql'   => 'Lengbin\YiiDb\Mssql\Schema', // older MSSQL driver on MS Windows hosts
        'dblib'   => 'Lengbin\YiiDb\Mssql\Schema', // dblib drivers on GNU/Linux (and maybe other OSes) hosts
        'cubrid'  => 'Lengbin\YiiDb\Cubrid\Schema', // CUBRID
    ];

    /**
     * Returns the schema information for the database opened by this connection.
     * @return Schema the schema information for the database opened by this connection.
     * @throws NotSupportedException if there is no support for the current driver type
     */
    public function getSchema()
    {
        if ($this->_schema !== null) {
            return $this->_schema;
        }

        $driver = $this->_driverName;
        if (isset($this->schemaMap[$driver])) {
            /**
             * @var Schema
             */
            $schema = new $this->schemaMap[$driver];
            $schema->db = $this;
            return $this->_schema = $schema;
        }

        throw new NotSupportedException("Connection does not support reading schema information for '$driver' DBMS.");
    }

    public function getQueryBuilder()
    {
        return $this->getSchema()->getQueryBuilder();
    }

    /**
     * Quotes a column name for use in a query.
     * If the column name contains prefix, the prefix will also be properly quoted.
     * If the column name is already quoted or contains special characters including '(', '[[' and '{{',
     * then this method will do nothing.
     *
     * @param string $name column name
     *
     * @return string the properly quoted column name
     */
    public function quoteColumnName($name)
    {
        if (isset($this->_quotedColumnNames[$name])) {
            return $this->_quotedColumnNames[$name];
        }
        return $this->_quotedColumnNames[$name] = $this->getSchema()->quoteColumnName($name);
    }

    /**
     * Quotes a string value for use in a query.
     * Note that if the parameter is not a string, it will be returned without change.
     *
     * @param string $value string to be quoted
     *
     * @return string the properly quoted string
     * @see https://secure.php.net/manual/en/pdo.quote.php
     */
    public function quoteValue($value)
    {
        return $this->getSchema()->quoteValue($value);
    }

    /**
     * Quotes a table name for use in a query.
     * If the table name contains schema prefix, the prefix will also be properly quoted.
     * If the table name is already quoted or contains special characters including '(', '[[' and '{{',
     * then this method will do nothing.
     *
     * @param string $name table name
     *
     * @return string the properly quoted table name
     */
    public function quoteTableName($name)
    {
        if (isset($this->_quotedTableNames[$name])) {
            return $this->_quotedTableNames[$name];
        }
        return $this->_quotedTableNames[$name] = $this->getSchema()->quoteTableName($name);
    }

    /**
     * Obtains the schema information for the named table.
     *
     * @param string $name    table name.
     * @param bool   $refresh whether to reload the table schema even if it is found in the cache.
     *
     * @return TableSchema table schema information. Null if the named table does not exist.
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTableSchema($name, $refresh = false)
    {
        return $this->getSchema()->getTableSchema($name, $refresh);
    }

    public function quoteSql($sql)
    {
        return preg_replace_callback(
            '/(\\{\\{(%?[\w\-\. ]+%?)\\}\\}|\\[\\[([\w\-\. ]+)\\]\\])/',
            function ($matches) {
                if (isset($matches[3])) {
                    return $this->quoteColumnName($matches[3]);
                }

                return str_replace('%', $this->tablePrefix, $this->quoteTableName($matches[2]));
            },
            $sql
        );
    }
}