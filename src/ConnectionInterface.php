<?php
/**
 * @link      http://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

declare(strict_types=1);

namespace Lengbin\YiiDb;

/**
 * DataBase connection interface.
 * XXX TODO to be continued...
 */
interface ConnectionInterface
{
    /**
     * 写库
     * @return mixed
     */
    public function getMasterPdo();

    /**
     * 读库
     *
     * @param bool $fallbackToMaster
     *
     * @return mixed
     */
    public function getSlavePdo($fallbackToMaster = true);

    /**
     * 事物
     * @param null $isolationLevel
     *
     * @return Transaction
     */
    public function beginTransaction($isolationLevel = null);

}
