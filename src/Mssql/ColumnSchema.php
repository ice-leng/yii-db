<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Lengbin\YiiDb\Mssql;

/**
 * Class ColumnSchema for MSSQL database
 *
 * @since 2.0.23
 */
class ColumnSchema extends \Lengbin\YiiDb\ColumnSchema
{
    /**
     * Prepares default value and converts it according to [[phpType]]
     * @param mixed $value default value
     * @return mixed converted value
     * @since 2.0.24
     */
    public function defaultPhpTypecast($value)
    {
        if ($value !== null) {
            // convert from MSSQL column_default format, e.g. ('1') -> 1, ('string') -> string
            $value = substr(substr($value, 2), 0, -2);
        }

        return parent::phpTypecast($value);
    }
}
