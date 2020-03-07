<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii2 db</h1>
    <br>
</p>

当前版本为测试版本
抽离yii2 的db 库, 使扩展可以集成到任何框架

安装
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require lengbin/yii-db
```

or add

```
"lengbin/yii-db": "*"
```
to the require section of your `composer.json` file.




Usage
-----

## 用法和yii 是一致的

### 方法一 依赖注入

```php
[
    \Lengbin\YiiDb\ConnectionInterface::class => Connection::class,
    // 可以不用实现， 记录sql操作日志
    \Psr\Log\LoggerInterface => Logger::class, 
]

```


### 方法二 直接使用

```php

$db = [
    'db' => [
        'class'    => 'yii\db\Connection',
        'charset'  => 'utf8mb4',
        'dsn'      => 'mysql:host=112.126.73.98;dbname=shuadan',
        'username' => 'root',
        'password' => '7fQi2uRPZpvbChzf',

    ],
];

$connect = new \Lengbin\YiiDb\Connection([
    'driver'  => 'mysql',
    'host'    => '127.0.0.1',
    'dbname'  => 'hyperf',
    'charset' => 'utf8',
]);
$connect->username = 'root';
$connect->password = '';

// 方法 一
//$query = new \Lengbin\YiiDb\Query();
//$query->from('demo');
//$data = $query->all($connect);
//$query->where(['id' => 79]);
//$data = $query->one($connect);

// 方法 二
//$query = new \Lengbin\YiiDb\Query($connect);
//$query->from('demo');
//$data = $query->all();
//$query->where(['id' => 79]);
//$data = $query->one();

class Demo extends \Lengbin\YiiDb\ActiveRecord\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'demo';
    }

    public static function getDb()
    {
//        $connect = new \Lengbin\YiiDb\Connection([
//            'driver'  => 'mysql',
//            'host'    => '127.0.0.1',
//            'dbname'  => 'hyperf',
//            'charset' => 'utf8',
//        ]);
//        $connect->username = 'root';
//        $connect->password = '';
//        return $connect;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_delete'], 'default', 'value' => 0],
            [['id', 'is_delete', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'name'       => '标题',
            'is_delete'  => '是否删除',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}

// select
// 方法 一
//$data = (new Demo())->find()->all($connect);
//$data = Demo::find()->all($connect);

//$data = (new Demo())->find()->where(['id' => 79])->one($connect);
//$data = Demo::find()->where(['id' => 79])->one($connect);

// 方法 二
//$data = (new Demo($connect))->find()->all();
//$data = (new Demo($connect))->find()->where(['id' => 79])->one();

// 方法三
// 需要 实现 getDb 返回 connection 对象
//$data = (new Demo())->find()->all();
//$data = Demo::find()->all();

//$data = (new Demo())->find()->where(['id' => 79])->one();
//$data = Demo::find()->where(['id' => 79])->one();

//insert
// 方法 一
//$model = (new Demo($connect));
//$model->setAttributes(['name' => 'hello2']);
//$data = $model->save();

// 方法 二
// 需要 实现 getDb 返回 connection 对象
//$model = (new Demo());
//$model->setAttributes(['name' => 'hello']);
//$data = $model->save();


//update
// 方法 一
//$model = (new Demo())->find()->where(['id' => 79])->one($connect);;
// 方法 二
//$model = (new Demo($connect))->find()->where(['id' => 79])->one();
// 方法三
// 需要 实现 getDb 返回 connection 对象
//$model = (new Demo())->find()->where(['id' => 79])->one();;

//$model->setAttributes(['name' => 'hello4']);
//$data = $model->save();

// delete
// 方法 一
//$model = (new Demo())->find()->where(['id' => 79])->one($connect);;
// 方法 二
//$model = (new Demo($connect))->find()->where(['id' => 79])->one();
// 方法三
// 需要 实现 getDb 返回 connection 对象
//$model = (new Demo())->find()->where(['id' => 79])->one();;

```



去掉不必要的
----
- behaviors
- 上传验证
- 图片验证
- 文件上传验证
- 验证码验证


其他
----

如果出现 minimum-stability冲突 [解决方案](https://blog.csdn.net/qq_32642039/article/details/78292685)


有问题请及时联系我，反正也会在使用中修复 - - ！


