<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

declare(strict_types=1);

namespace Lengbin\YiiDb\Cubrid\Conditions;

/**
 * {@inheritdoc}
 */
class LikeConditionBuilder extends \Lengbin\YiiDb\Conditions\LikeConditionBuilder
{
    /**
     * {@inheritdoc}
     */
    protected $escapeCharacter = '!';
    /**
     * `\` is initialized in [[buildLikeCondition()]] method since
     * we need to choose replacement value based on [[\yii\db\Schema::quoteValue()]].
     * {@inheritdoc}
     */
    protected $escapingReplacements = [
        '%' => '!%',
        '_' => '!_',
        '!' => '!!',
    ];
}
