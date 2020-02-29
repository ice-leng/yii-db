<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Lengbin\YiiDb\Sqlite\Conditions;

/**
 * {@inheritdoc}
 */
class LikeConditionBuilder extends \Lengbin\YiiDb\Conditions\LikeConditionBuilder
{
    /**
     * {@inheritdoc}
     */
    protected $escapeCharacter = '\\';
}
