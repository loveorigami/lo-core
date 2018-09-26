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
 * Class for memorize function|method calling
 *
 * E.g:
 * ```php
 *
 * $result1 = Memorize::call([Product::className(), 'getSkuMap']);   // slow operation
 * $result2 = Memorize::call([Product::className(), 'getSkuMap']);   // next call will be faster
 *
 * @copyright 2016 NRE
 */
class Memorize
{
    protected static $cache = [];

    /**
     * Call memorize method
     *
     * @param $func
     * @param array $args
     * @return mixed
     */
    public static function call($func, array $args = [])
    {
        return \call_user_func_array(self::get($func), $args);
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
            self::$cache[$key] = self::getMemorize($func);
        }
        return self::$cache[$key];
    }

    /**
     * Create decorated method
     *
     * @param $func
     * @return \Closure
     */
    protected static function getMemorize($func): callable
    {
        return function () use ($func) {
            static $cache = [];
            $args = \func_get_args();
            $key = md5(serialize($args));
            if (!array_key_exists($key, $cache)) {
                $cache[$key] = \call_user_func_array($func, $args);
            }
            return $cache[$key];
        };
    }
}
