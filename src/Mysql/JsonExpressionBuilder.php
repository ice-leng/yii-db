<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

declare(strict_types=1);

namespace Lengbin\YiiDb\Mysql;

use Lengbin\YiiDb\ExpressionBuilderInterface;
use Lengbin\YiiDb\ExpressionBuilderTrait;
use Lengbin\YiiDb\ExpressionInterface;
use Lengbin\YiiDb\JsonExpression;
use Lengbin\YiiDb\Query;

/**
 * Class JsonExpressionBuilder builds [[JsonExpression]] for MySQL DBMS.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 * @since 2.0.14
 */
class JsonExpressionBuilder implements ExpressionBuilderInterface
{
    use ExpressionBuilderTrait;

    const PARAM_PREFIX = ':qp';


    /**
     * {@inheritdoc}
     * @param JsonExpression|ExpressionInterface $expression the expression to be built
     */
    public function build(ExpressionInterface $expression, array &$params = [])
    {
        $value = $expression->getValue();

        if ($value instanceof Query) {
            list ($sql, $params) = $this->queryBuilder->build($value, $params);
            return "($sql)";
        }

        $placeholder = static::PARAM_PREFIX . count($params);
        $params[$placeholder] = json_encode($value);

        return "CAST($placeholder AS JSON)";
    }
}
