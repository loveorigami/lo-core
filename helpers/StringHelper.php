<?php

namespace lo\core\helpers;

use yii\helpers\StringHelper as YiiStringHelper;

/**
 * Class StringHelper
 * Содержит методы для работы со строками
 * @package lo\core\helpers
 * @author Andrey Lukyanov
 */
class StringHelper extends YiiStringHelper
{

    /**
     * Возвращает строку, обрезая по последний разделитель
     * @param string $str строка
     * @param string $delimiter разделитель
     * @param bool $with_delimiter вместе с разделителем
     * @return string
     */

    public static function strToDelimiter($str, $delimiter = '/', $with_delimiter = true)
    {
        //$str = preg_replace('~[^$delimiter]+$~s', '', $str); // вариант

        $count = $with_delimiter ? 0 : 1;

        $str = rtrim($str, $delimiter);
        $str = substr($str, 0, strrpos($str, $delimiter) + $count);

        return $str;
    }

}