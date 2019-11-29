<?php

use Lengbin\YiiDb\Query\BaseQuery;

/**
// 创建 Query。php 继承 Lengbin\YiiDb\Query\BaseQuery
// 覆写一下功能
 */
class Query extends BaseQuery
{

    // 是否开启中文翻译
    // 默认为true, 如果 false, message 是英文
    public $isTranslate = true;

    /**
     *
     * 创建一个连接数据库实例对象
     *
     * @return object
     */
    public function getDb()
    {
        return new Db();
    }

    /**
     * 数据库连接唯一标识， 随便定义
     * 为了防止多库导致表名相同导致缓存数据覆盖
     * 用于缓存表字段（表结构）
     */
    public function databaseUniqueId()
    {
        return 'demo';
    }

    /**
     * 缓存类
     *
     * 实现 \Psr\SimpleCache\CacheInterface
     * 缓存实例
     *
     * @return \Psr\SimpleCache\CacheInterface
     */
    public function getSchemaCache()
    {
        return '\Psr\SimpleCache\CacheInterface';
    }

    /**
     * Query constructor.
     *
     * 获得所有数据
     *
     * @param string $sql
     * @param array  $params
     * @return array
     */
    public function query(string $sql, array $params = [])
    {
        return $this->getDb()->query($sql, $params);
    }

    /**
     * 获得一条数据
     *
     * @param string $sql
     * @param array  $params
     *
     * @return array
     */
    public function queryOne(string $sql, array $params = [])
    {
        return $this->getDb()->queryOne($sql, $params);
    }

    /**
     * 执行
     * @param string $sql
     * @param array  $params
     * @return bool
     */
    public function execute(string $sql, array $params = [])
    {
        return $this->getDb()->execute($sql, $params);
    }

    /**
     * 最新添加的 id
     * @param string $sequenceName
     */
    public function lastInsertId($sequenceName = '')
    {
        return $this->getDb()->getPdo()->lastInsertId($sequenceName);
    }

}