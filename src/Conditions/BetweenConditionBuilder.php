<?php
/**
 * @link http://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

declare(strict_types=1);

namespace Lengbin\YiiDb\Conditions;

use Lengbin\YiiDb\ExpressionBuilderInterface;
use Lengbin\YiiDb\ExpressionBuilderTrait;
use Lengbin\YiiDb\ExpressionInterface;

/**
 * Class BetweenConditionBuilder builds objects of [[BetweenCondition]].
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @since 2.0.14
 */
class BetweenConditionBuilder implements ExpressionBuilderInterface
{
    use ExpressionBuilderTrait;

    /**
     * Method builds the raw SQL from the $expression that will not be additionally
     * escaped or quoted.
     *
     * @param ExpressionInterface|BetweenCondition $expression the expression to be built.
     * @param array                                $params     the binding parameters.
     *
     * @return string the raw SQL that will not be additionally escaped or quoted.
     * @throws \Lengbin\YiiDb\Exception\Exception
     * @throws \Lengbin\YiiDb\Exception\InvalidConfigException
     * @throws \Lengbin\YiiDb\Exception\NotSupportedException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function build(ExpressionInterface $expression, array &$params = [])
    {
        $operator = $expression->getOperator();
        $column = $expression->getColumn();

        if (strpos($column, '(') === false) {
            $column = $this->queryBuilder->db->quoteColumnName($column);
        }

        $phName1 = $this->createPlaceholder($expression->getIntervalStart(), $params);
        $phName2 = $this->createPlaceholder($expression->getIntervalEnd(), $params);

        return "$column $operator $phName1 AND $phName2";
    }

    /**
     * Attaches $value to $params array and returns placeholder.
     *
     * @param mixed $value
     * @param array $params passed by reference
     *
     * @return string
     */
    protected function createPlaceholder($value, &$params)
    {
        if ($value instanceof ExpressionInterface) {
            return $this->queryBuilder->buildExpression($value, $params);
        }

        return $this->queryBuilder->bindParam($value, $params);
    }
}
