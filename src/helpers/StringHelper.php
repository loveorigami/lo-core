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
     * Получим все ссылки из текста
     * @param string $text - текст
     * @return array
     */
    function getArrUrl($text){
        preg_match_all(
            "#\s(?:href|src|url)=(?:[\"\'])?(.*?)(?:[\"\'])?(?:[\s\>])#i",
            $text,
            $matches
        );
        return $matches[1];
    }

    /**
     * Получим все ссылки из текста
     * @param string $text - текст
     * @return array
     */
    function getArrSrc($text){
        preg_match_all(
            "#\s(?:src)=(?:[\"\'])?(.*?)(?:[\"\'])?(?:[\s\>])#i",
            $text,
            $matches
        );
        return $matches[1];
    }

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

    /**
     * @param string $str
     * @param bool $md5
     * @return mixed|string
     */
    public static function strMd5($str = '', $md5 = true)
    {
        // Удаляем текст в []
/*        preg_match('/\[([A-Za-z\d_]+)\]/us', $str, $sup);

        if (isset($sup[0])) {
            $str = str_replace($sup[0], '', $str);
        }*/

        // Удаляем теги
        $str = strip_tags($str);

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

    /**
     * @param $str
     * @return string
     */
    public static function convToUtf8($str)
    {
        return iconv("windows-1251", "utf-8", $str);
    }

    /**
     * @param $str
     * @return string
     */
    public static function trim($str)
    {
        $str = preg_replace("/(^\s+)|(\s+$)/us", "", $str);
        return $str;
    }

    /**
     * mb_ucfirst - make a string's first character uppercase
     * @param $str - the input string.
     * @param string $encoding - string $encoding [optional] &mbstring.encoding.parameter; default UTF-8
     * @return string - string str with first alphabetic character converted to uppercase.
     */
    public static function ucfirst($str, $encoding = 'UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

    /**
     * @param $str - the input string.
     * @return string
     */
    public static function strtolower($str)
    {
        $str = mb_strtolower($str);
        return $str;
    }

    /**
     * @param $str - the input string.
     * @return string
     */
    public static function strtoupper($str)
    {
        $str = mb_strtoupper($str);
        return $str;
    }
}