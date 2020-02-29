<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Lengbin\YiiDb\Oci\Conditions;

use Lengbin\YiiDb\ExpressionInterface;

/**
 * {@inheritdoc}
 */
class LikeConditionBuilder extends \Lengbin\YiiDb\conditions\LikeConditionBuilder
{
    /**
     * {@inheritdoc}
     */
    protected $escapeCharacter = '!';
    /**
     * `\` is initialized in [[buildLikeCondition()]] method since
     * we need to choose replacement value based on [[\Lengbin\YiiDb\Schema::quoteValue()]].
     * {@inheritdoc}
     */
    protected $escapingReplacements = [
        '%' => '!%',
        '_' => '!_',
        '!' => '!!',
    ];


    /**
     * {@inheritdoc}
     */
    public function build(ExpressionInterface $expression, array &$params = [])
    {
        if (!isset($this->escapingReplacements['\\'])) {
            /*
             * Different pdo_oci8 versions may or may not implement PDO::quote(), so
             * Lengbin\YiiDb\Schema::quoteValue() may or may not quote \.
             */
            $this->escapingReplacements['\\'] = substr($this->queryBuilder->db->quoteValue('\\'), 1, -1);
        }

        return parent::build($expression, $params);
    }
}
