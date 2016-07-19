<?php

namespace lo\core\helpers;

use yii\helpers\ArrayHelper as YiiArrayHelper;

/**
 * Class ArrayHelper
 * Содержит методы для работы с массивами
 * @package lo\core\helpers
 * @author Andrey Lukyanov
 */
class ArrayHelper extends YiiArrayHelper
{

    /**
     * Трансформирует nested-set массив к массиву с вложенными ветками
     *
     * Исходный массив
     * ```php
     *  [
     *      [
     *          [id] => 1
     *          [level] => 1
     *          [lft] => 1
     *          [rgt] => 8
     *          [name] => parent
     *      ],
     *      [
     *          [id] => 2
     *          [level] => 2
     *          [lft] => 2
     *          [rgt] => 7
     *          [name] => node
     *      ]
     *  ]
     *  ```
     *
     *  Результирующий массив
     * ```php
     *  [
     *      [
     *          [id] => 1
     *          [level] => 1
     *          [lft] => 1
     *          [rgt] => 8
     *          [name] => parent
     *          [items] => [
     *              [
     *                  [id] => 2
     *                  [level] => 2
     *                  [lft] => 2
     *                  [rgt] => 7
     *                  [name] => node
     *              ]
     *          ]
     *      ]
     *  ]
     * ```
     * @param array
     * @return array
     */
    public static function nestedToNodes($nested_array)
    {
        $result = [];
        $stack = [];

        foreach ($nested_array as $node) {

            $item = $node;
            $level = count($stack);

            while ($level > 0 && $stack[$level - 1]['level'] >= $item['level']) {
                array_pop($stack);
                $level--;
            }

            if ($level == 0) {
                $i = count($result);
                $result[$i] = $item;
                $stack[] = &$result[$i];
            } else {
                $i = count($stack[$level - 1]['items']);
                $stack[$level - 1]['items'][$i] = $item;
                $stack[] = &$stack[$level - 1]['items'][$i];
            }
        }

        $tree = $result;

        return $tree;
    }

    /**
     * @param $arr
     * @return mixed
     */
    public static function multi_order($arr)
    {
        $ar2 = [];
        $i = 0;
        $n = 5;
        foreach ($arr as $a) {
            $ar2[$i] = (isset($a['pos'])) ? $a['pos'] : $n++;
            $i++;
        }
        array_multisort($arr, SORT_NUMERIC, $ar2);
        return ($arr);
    }

    /**
     * mb_ucfirst - make a string's first character uppercase
     * @param $str - the input string.
     * @param string $encoding - string $encoding [optional] &mbstring.encoding.parameter; default UTF-8
     * @return string - string str with first alphabetic character converted to uppercase.
     */
    public static function mb_ucfirst($str, $encoding = 'UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }
}