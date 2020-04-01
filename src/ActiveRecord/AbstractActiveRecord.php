<?php

/**
 * Created by PhpStorm.
 * User: lengbin
 * Date: 2017/2/6
 * Time: 下午3:17
 */

namespace Lengbin\YiiDb\ActiveRecord;

use Lengbin\Helper\Util\SnowFlakeHelper;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;
use Lengbin\YiiDb\Exception\Exception;
use Lengbin\YiiDb\Exception\InvalidConfigException;
use Lengbin\YiiDb\Pagination;
use Lengbin\YiiDb\Query;
use Lengbin\YiiDb\Validators\Validator;
use Lengbin\YiiDb\ConnectionInterface;

class AbstractActiveRecord extends ActiveRecord
{

    public function __construct(ConnectionInterface $connection = null, array $config = [])
    {
        $this->on(self::BEFORE_INSERT, [$this, 'saveBeforeInsert']);
        $this->on(self::BEFORE_UPDATE, [$this, 'saveBeforeUpdate']);
        parent::__construct($connection, $config);
    }

    protected function saveBeforeInsert($event)
    {
        if ($this->hasAttribute('is_delete') && empty($this->is_delete)) {
            $this->is_delete = 0;
        }

        if ($this->hasAttribute('is_stop') && empty($this->is_stop)) {
            $this->is_stop = 0;
        }

        $time = time();
        if ($this->hasAttribute('created_at') && empty($this->created_at)) {
            $this->created_at = $time;
        }

        if ($this->hasAttribute('updated_at') && empty($this->updated_at)) {
            $this->updated_at = $time;
        }
    }

    protected function saveBeforeUpdate($event)
    {
        if ($this->hasAttribute('updated_at')) {
            $this->updated_at = time();
        }
    }

    /**
     * 获得 32位 唯一 字符串
     *
     * @param     $service_no
     *
     * @return string
     */
    public function nextId0($service_no = 1)
    {
        SnowFlakeHelper::machineId($service_no);
        return SnowFlakeHelper::generateParticle();
    }

    /**
     * 获得 32位 唯一 字符串
     *
     * 推特分布式id生成算法SnowFlake PHP 的实现
     *
     * 用于唯一主键
     *
     * @param $type
     * @param $service_no
     *
     * @return string
     * @see https://github.com/Sxdd/php_snowflake
     *
     */
    public function nextId($service_no = 1)
    {
        try {
            $id = \PhpSnowFlake::nextId($service_no);
            $number = str_replace('-', 0, $id);
        } catch (\Throwable $exception) {
            $number = $this->nextId0($service_no);
        }
        return $number;
    }

    /**
     * lbs 2点计算距离
     *
     * @param float  $lat 纬度
     * @param float  $lng 经度
     * @param string $latName
     * @param string $lngName
     *
     * @return string
     */
    public function distanceSql($lat, $lng, $latName = 'lat', $lngName = 'lng')
    {
        $sql = "(2 * 6371.393 * ASIN(SQRT(COS( 23.99 * PI( ) / 180 ) * COS( 23.99 * PI( ) / 180 ) * POW( SIN( ( {$lng} - {$lngName} ) * PI( ) / 360 ), 2 ) + POW( SIN( ( {$lat} - {$latName} ) * PI( ) / 360 ), 2 )) ) * 1000 ) ";
        return $sql;
    }

    /**
     * debug get sql
     *
     * @param ActiveQuery $query
     *
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \Lengbin\YiiDb\Exception\NotSupportedException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function debugForQuery(ActiveQuery $query)
    {
        echo $query->createCommand()->getRawSql();
        die;
    }

    /**
     * 重构 获得第一个错误， 是否数组 还是 字符串
     *
     * @param bool $isArray
     *
     * @return array|string|null
     */
    public function getFirstErrors($isArray = true)
    {
        $error = parent::getFirstErrors();
        return $isArray ? $error : current($error);
    }

    /**
     * // todo
     * 重构 创建规则规则
     *
     * 添加 字段 trim
     *
     * @return \ArrayObject
     * @throws InvalidConfigException
     */
    public function createValidators()
    {
        $rules = $this->rules();
        $key = [];
        foreach ($this->getAttributes() as $name => $value) {
            if ($this->isAttributeChanged($name) && is_string($value)) {
                $key[] = $name;
            }
        }
        array_unshift($rules, [$key, 'trim']);
        $validators = new \ArrayObject();
        foreach ($rules as $rule) {
            if ($rule instanceof Validator) {
                $validators->append($rule);
            } elseif (is_array($rule) && isset($rule[0], $rule[1])) { // attributes, validator type
                $validator = Validator::createValidator($rule[1], $this, (array)$rule[0], array_slice($rule, 2));
                $validators->append($validator);
            } else {
                throw new InvalidConfigException('Invalid validation rule: a rule must specify both attribute names and validator type.');
            }
        }
        return $validators;
    }

    /**
     * 通过id获得数据
     *
     * @param int    $id
     * @param string $isDeleteName 是否条件有is_delete
     *
     * @return array|null|ActiveRecord
     */
    public function getById($id, $isDeleteName = 'is_delete')
    {
        $params = ['id' => $id];
        if (!empty($isDeleteName)) {
            $params[$isDeleteName] = 0;
        }
        return $this->find()->where($params)->one();
    }

    /**
     * 通过id获得数据
     *
     * @param array  $id
     * @param string $isDeleteName 是否条件有is_delete
     *
     * @return array|null|ActiveRecord
     */
    public function getByIds(array $id, $isDeleteName = 'is_delete')
    {
        $params = ['id' => $id];
        if (!empty($isDeleteName)) {
            $params[$isDeleteName] = 0;
        }
        return $this->find()->where($params)->all();
    }

    /**
     * 获得有效参数
     *
     * @param array $params
     *
     * @return mixed
     * @throws InvalidConfigException
     */
    public function filterParams(array $params)
    {
        return ArrayHelper::getValue($params, $this->formName(), $params);
    }

    public $isAddRecord = false;

    /**
     * 添加 / 更新
     *
     * @param array  $params
     * @param string $isDeleteName
     *
     * @return array|ActiveRecord|null|ActiveRecord
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function updateByParams(array $params, $isDeleteName = 'is_delete')
    {
        $params = $this->filterParams($params);
        $object = $this;
        if (!$this->isAddRecord && !empty($params['id'])) {
            $object = $this->getById($params['id'], $isDeleteName);
            if (empty($object)) {
                return $object;
            }
        }
        $object->scenario = $this->scenario;
        $object->setAttributes($params);
        $object->save();
        return $object;
    }

    /**
     * 通过id删除数据
     *
     * @param int    $id
     * @param string $isDeleteName
     *
     * @return false|int
     * @throws \Throwable
     */
    public function deleteById($id, $isDeleteName = 'is_delete')
    {
        $model = $this->getById($id, $isDeleteName);
        if (empty($model)) {
            return false;
        }
        if (!empty($isDeleteName)) {
            $model->$isDeleteName = 1;
            $result = $model->save();
        } else {
            $result = $model->delete();
        }
        return $result;
    }

    /**
     * 通过ids删除数据
     *
     * @param array  $id
     * @param string $isDeleteName
     *
     * @return int
     * @throws Exception
     */
    public function deleteByIds(array $id, $isDeleteName = 'is_delete')
    {
        $where = ['id' => $id];
        return !empty($isDeleteName) ? $this->updateAll([$isDeleteName => 1], $where) : self::deleteAll($where);
    }

    /**
     * 累加或者累减
     *
     * 更新计数
     *
     * 如果用save 高并发容易错误
     *
     * @param array  $params
     * @param string $id
     *
     * @return bool
     */
    public function updateCount(array $params, $id = '')
    {
        if (!$this->isNewRecord) {
            return $this->updateCounters($params);
        }
        $model = $this->getById($id, false);
        if (empty($model)) {
            return false;
        }
        return $model->updateCounters($params);
    }

    /**
     * 分页
     *
     * @param       $model
     * @param int   $pageSize
     * @param array $params
     *
     * @return array
     * @throws \Lengbin\YiiDb\Exception\Exception
     * @throws \Lengbin\YiiDb\Exception\InvalidConfigException
     * @throws \Lengbin\YiiDb\Exception\NotSupportedException
     * @throws \Throwable
     */
    public function page($model, $pageSize = 20, array $params = [])
    {
        if ($model instanceof Query) {
            $count = $model->count();
        } else {
            $count = count($model);
        }
        $pages = new Pagination([
            'params'          => $params,
            'totalCount'      => $count,
            'defaultPageSize' => $pageSize,
        ]);
        if ($model instanceof Query) {
            $models = $model->offset($pages->offset)->limit($pages->limit)->all();
        } else {
            /* @var $model array */
            $models = array_slice($model, $pages->offset, $pages->limit);
        }
        return [
            'models' => $models,
            'pages'  => $pages,
        ];
    }
}
