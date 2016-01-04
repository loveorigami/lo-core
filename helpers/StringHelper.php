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

    public static function strMd5($str = '', $md5 = true)
    {
        // Удаляем текст в []
            preg_match('/\[([A-Za-z\d_]+)\]/us', $str, $sup);

            if(isset($sup[0])){
                $str=str_replace($sup[0], '', $str);
            }

        // Удаляем все слова меньше 3-х символов
        $str = htmlspecialchars_decode($str);
        $str = mb_strtolower($str, 'utf-8');
        $str = preg_replace("|\b[\d\w]{1,3}\b|us", "", $str);

        // Удаляем знаки припенания
        $pattern = "/[\w\s\d]+/u";
        preg_match_all($pattern, $str, $result);
        $str = implode('', $result[0]);

        // Удаляем лишние пробельные символы
        $str = preg_replace("/[\s]+/us", "", $str);

        if ($md5) $str = md5($str);

        return $str;
    }

    public static function convToUtf8($str)
    {
        return iconv("windows-1251", "utf-8", $str);
    }

}