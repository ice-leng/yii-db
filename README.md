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
composer require lengbin/yii-db "*"
```

or add

```
"lengbin/yii-db": "*"
```
to the require section of your `composer.json` file.

扩展 只是提供了yii db 的查询器和验证器
数据库实例相关需要自己去实现，配置如下图

Configs
-----

``` php

    

```

Usage
-----
用法和yii 是一致的


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


