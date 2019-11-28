<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii2 db</h1>
    <br>
</p>

当前版本为测试版本

抽离yii2 的db 库, 使扩展可以集成到任何框架
目前抽离
- Query
- ActiveRecode
- Validator
- Event
- db
	- Mysql

安装
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require lengbin/yii-db "*"
```

or add

```
"lengbin/yii-db": "*"
```

to the require section of your `composer.json` file.


Configs
-----
demo文件有测试案例
``` php
//The scan directories, you should use real path there.
Query。php
<?php

use Lengbin\YiiDb\Query\BaseQuery;

class Query extends BaseQuery
{

    /**
     *
     * 连接数据库对象
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


ActiveRecode.php
<?php

class ActiveRecord extends \Lengbin\YiiDb\ActiveRecord\ActiveRecord
{
    // 实现 的 query 对象
    public static function getDb()
    {
        return new Query();
    }
}

```

Usage
-----
用法和yii 是一致的

```php

            $demo = new Demo();
    //        $demo = Demo::findOne('1');
    //        $data = $demo->find()->all();
            $demo->setAttributes(['name' => '2']);
            $data = $demo->save();
    
    //        $query = new Query();
    //        $query->from(['d' => 'demo']);
    //            ->leftJoin(['d2' => 'demo2'], 'd2.demo_id = d.id')
    //            ->select(['dn' => 'd2.name', 'd.id', 'd.name']);
    //        $data =  $query->all();
    
```

tests
------
- Query
  - from
  - select
  - all
  - one
  - leftJon
- ActiveReode
  - insert
  - update
- Validate
  - required
  - string
  - default
- Event
  - EVENT_BEFORE_INSERT
  - EVENT_BEFORE_UPDATE


其他
----

有问题请及时联系我，反正也会在使用中修复 - - ！


