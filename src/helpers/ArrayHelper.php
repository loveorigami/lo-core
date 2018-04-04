<?php

namespace lo\core\helpers;

use Closure;
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
     * @param $array
     * @return mixed
     */
    public static function multiorder($array)
    {
        $ar2 = [];
        $i = 0;
        $n = 5;
        foreach ($array as $a) {
            $ar2[$i] = (isset($a['pos'])) ? $a['pos'] : $n++;
            $i++;
        }
        array_multisort($array, SORT_NUMERIC, $ar2);
        return ($array);
    }

    /**
     * Return the first element in an array passing a given truth test.
     * @param  array $array
     * @param  \Closure $callback
     * @param  mixed $default
     * @return mixed
     * @see https://github.com/yii2mod/yii2-helpers/blob/master/ArrayHelper.php#L245
     */
    public static function first($array, $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? static::value($default) : reset($array);
        }
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value)) {
                return $value;
            }
        }
        return static::value($default);
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

    /**
     * Created for do to search inside of array.
     * if you do $all is 1, all results will return to array
     * ------------------
     * Example:
     *  $query  =  "a='Example World' and b>='2'";
     *  $array  = [
     *      'a' => ['d' => '2'],
     *      ['a' => 'Example World','b' => '2'],
     *      ['c' => '3'],
     *      ['d' => '4'],
     *  ];
     *  $result = ArrayHelper::q($aray,$query,1);
     * ------------------
     * @param $SearchArray
     * @param $query
     * @param int $all
     * @param string $Return
     * @return array|bool|int|string
     */
    static function q($SearchArray, $query, $all = 0, $Return = 'direct')
    {
        $SearchArray = json_decode(json_encode($SearchArray), true);
        $ResultArray = array();
        if (is_array($SearchArray)) {
            $desen = "@[\s*]?[\'{1}]?([a-zA-Z\ç\Ç\ö\Ö\ş\Ş\ı\İ\ğ\Ğ\ü\Ü[:space:]0-9-_:;]*)[\'{1}]?[\s*]?(\<\=|\>\=|\=|\!\=|\<|\>)\s*\'([a-zA-Z\ç\Ç\ö\Ö\ş\Ş\ı\İ\ğ\Ğ\ü\Ü[:space:]0-9-_:;]*)\'[\s*]?(and|or|\&\&|\|\|)?@si";
            $DonenSonuc = preg_match_all($desen, $query, $Result);
            if ($DonenSonuc) {
                foreach ($SearchArray as $i => $ArrayElement) {
                    $SearchStatus = 0;
                    $EvalString = "";
                    for ($r = 0; $r < count($Result[1]); $r++):
                        if ($Result[2][$r] == '=') {
                            $Operator = "==";
                        } elseif ($Result[2][$r] == '!=') {
                            $Operator = "!=";
                        } elseif ($Result[2][$r] == '>=') {
                            $Operator = ">=";
                        } elseif ($Result[2][$r] == '<=') {
                            $Operator = "<=";
                        } elseif ($Result[2][$r] == '>') {
                            $Operator = ">";
                        } elseif ($Result[2][$r] == '<') {
                            $Operator = "<";
                        } else {
                            $Operator = "==";
                        }
                        $AndOperator = "";
                        if ($r != count($Result[1]) - 1) {
                            $AndOperator = $Result[4][$r] ?: 'and';
                        }
                        $EvalString .= '("' . $ArrayElement[$Result[1][$r]] . '"' . $Operator . '"' . $Result[3][$r] . '") ' . $AndOperator . ' ';
                    endfor;
                    eval('if( ' . $EvalString . ' ) $SearchStatus = 1;');
                    if ($SearchStatus === 1) {
                        if ($all === 1) {
                            if ($Return == 'direct') :
                                $ResultArray[] = $ArrayElement;
                            elseif ($Return == 'array') :
                                $ResultArray['index'][] = $i;
                                $ResultArray['array'][] = $ArrayElement;
                            endif;
                        } else {
                            if ($Return == 'direct') :
                                $ResultArray = $i;
                            elseif ($Return == 'array') :
                                $ResultArray['index'] = $i;
                            endif;
                            return $ResultArray;
                        }
                    }
                }
                if ($all === 1) {
                    return $ResultArray;
                }
            }
        }
        return false;
    }
}