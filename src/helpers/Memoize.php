<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 05.06.2018
 * Time: 13:13
 */

namespace lo\core\helpers;

/**
 *
 * Class for memoize function|method calling
 *
 * E.g:
 * ```php
 *
 * $result1 = Memoize::call([Product::className(), 'getSkuMap']);   // slow operation
 * $result2 = Memoize::call([Product::className(), 'getSkuMap']);   // next call will be faster
 *
 * @copyright 2016 NRE
 */
class Memoize
{
    protected static $cache = [];

    /**
     * Call memoize method
     *
     * @param $func
     * @param array $args
     * @return mixed
     */
    public static function call($func, $args = [])
    {
        return call_user_func_array(self::get($func), $args);
    }

    /**
     * Return decorated method
     * @param $func
     * @return mixed
     */
    protected static function get($func)
    {
        $key = md5(serialize($func));
        if (!array_key_exists($key, self::$cache)) {
            self::$cache[$key] = self::getMemoize($func);
        }
        return self::$cache[$key];
    }

    /**
     * Create decorated method
     *
     * @param $func
     * @return \Closure
     */
    protected static function getMemoize($func)
    {
        return function () use ($func) {
            static $cache = [];
            $args = func_get_args();
            $key = md5(serialize($args));
            if (!array_key_exists($key, $cache)) {
                $cache[$key] = call_user_func_array($func, $args);
            }
            return $cache[$key];
        };
    }
}