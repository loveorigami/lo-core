<?php

namespace lo\core\helpers;

use lo\core\exceptions\InvalidVariableTypeException;

/**
 * Class VarHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class VarHelper
{
    const TYPE_TEXT_COLOR_BLACK = 1;
    const TYPE_TEXT_COLOR_WHITE = 2;
    const TYPE_ARRAY = 1;
    const TYPE_BOOLEAN = 2;
    const TYPE_CALLABLE = 3;
    const TYPE_DOUBLE = 4;
    const TYPE_FLOAT = 5;
    const TYPE_INT = 6;
    const TYPE_INTEGER = 7;
    const TYPE_LONG = 8;
    const TYPE_NULL = 9;
    const TYPE_NUMERIC = 10;
    const TYPE_OBJECT = 11;
    const TYPE_RESOURCE = 13;
    const TYPE_SCALAR = 14;
    const TYPE_STRING = 15;

    /**
     * @param mixed $variable
     * @param int $color
     */
    public static function dump($variable, $color = self::TYPE_TEXT_COLOR_BLACK)
    {
        $color = (($color == self::TYPE_TEXT_COLOR_BLACK) ? '#000000' : '#FFFFFF');
        echo '<pre style="color:' . $color . '">';
        var_dump($variable);
        echo '</pre>';
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isEmpty($variable)
    {
        if (is_string($variable)) {
            $variable = trim($variable);
        }
        return (!isset($variable) || empty($variable));
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isNull($variable)
    {
        return is_null($variable);
    }

    /**
     * @param mixed $variable
     * @return string
     */
    public static function getType($variable)
    {
        return gettype($variable);
    }

    /**
     * @param mixed $variable
     * @param int $type
     * @param bool $raiseError
     * @return void
     * @throws InvalidVariableTypeException
     * @see VarHelper::force
     */

    public static function forceType($variable, $type = self::TYPE_STRING, $raiseError = false)
    {
        static::force($variable, $type, $raiseError);
    }

    /**
     * @param $variable
     * @param int $type
     * @param bool $raiseError
     * @return array|bool|float
     * @throws InvalidVariableTypeException
     */
    public static function force($variable, $type = self::TYPE_STRING, $raiseError = false)
    {
        if (!in_array($type, [
            self::TYPE_ARRAY,
            self::TYPE_BOOLEAN,
            self::TYPE_CALLABLE,
            self::TYPE_DOUBLE,
            self::TYPE_FLOAT,
            self::TYPE_INT,
            self::TYPE_INTEGER,
            self::TYPE_LONG,
            self::TYPE_NULL,
            self::TYPE_NUMERIC,
            self::TYPE_OBJECT,
            self::TYPE_RESOURCE,
            self::TYPE_SCALAR,
            self::TYPE_STRING
        ])
        ) {
            return null;
        }

        switch ($type) {
            case self::TYPE_ARRAY: {
                if ($raiseError) {
                    if (!is_array($variable)) {
                        throw new InvalidVariableTypeException('Array');
                    }
                } else {
                    $variable = (array)$variable;
                }
                break;
            }
            case self::TYPE_BOOLEAN: {
                if ($raiseError) {
                    if (!is_bool($variable)) {
                        throw new InvalidVariableTypeException('Boolean');
                    }
                } else {
                    $variable = (bool)$variable;
                }
                break;
            }
            case self::TYPE_CALLABLE: {
                if ($raiseError) {
                    if (!is_callable($variable)) {
                        throw new InvalidVariableTypeException('Callable');
                    }
                } else {
                    $variable = null;
                }
                break;
            }
            case self::TYPE_DOUBLE: {
                if ($raiseError) {
                    if (!is_double($variable)) {
                        throw new InvalidVariableTypeException('Double');
                    }
                } else {
                    $variable = (double)$variable;
                }
                break;
            }
            case self::TYPE_FLOAT: {
                if ($raiseError) {
                    if (!is_float($variable)) {
                        throw new InvalidVariableTypeException('Float');
                    }
                } else {
                    $variable = (float)$variable;
                }
                break;
            }
            case self::TYPE_INT: {
                if ($raiseError) {
                    if (!is_integer($variable)) {
                        throw new InvalidVariableTypeException('Integer');
                    }
                } else {
                    $variable = (int)$variable;
                }
                break;
            }
            case self::TYPE_INTEGER: {
                if ($raiseError) {
                    if (!is_integer($variable)) {
                        throw new InvalidVariableTypeException('Integer');
                    }
                } else {
                    $variable = (int)$variable;
                }
                break;
            }
            case self::TYPE_LONG: {
                if ($raiseError) {
                    if (!is_long($variable)) {
                        throw new InvalidVariableTypeException('Long');
                    }
                } else {
                    $variable = (int)$variable;
                }
                break;
            }
            case self::TYPE_NULL: {
                if ($raiseError) {
                    if (!is_null($variable)) {
                        throw new InvalidVariableTypeException('Null');
                    }
                } else {
                    $variable = null;
                }
                break;
            }
            case self::TYPE_NUMERIC: {
                if ($raiseError) {
                    if (!is_numeric($variable)) {
                        throw new InvalidVariableTypeException('Numeric');
                    }
                } else {
                    $variable = (int)$variable;
                }
                break;
            }
            case self::TYPE_OBJECT: {
                if ($raiseError) {
                    if (!is_object($variable)) {
                        throw new InvalidVariableTypeException('Object');
                    }
                } else {
                    $variable = (object)$variable;
                }
                break;
            }

            case self::TYPE_RESOURCE: {
                if ($raiseError) {
                    if (!is_resource($variable)) {
                        throw new InvalidVariableTypeException('Resource');
                    }
                } else {
                    $variable = null;
                }
                break;
            }
            case self::TYPE_SCALAR: {
                if ($raiseError) {
                    if (!is_scalar($variable)) {
                        throw new InvalidVariableTypeException('Scalar');
                    }
                } else {
                    $variable = (string)$variable;
                }
                break;
            }
            case self::TYPE_STRING: {
                if ($raiseError) {
                    if (!is_string($variable)) {
                        throw new InvalidVariableTypeException('String');
                    }
                } else {
                    $variable = (string)$variable;
                }
                break;
            }
            default: {
                $variable = null;
            }
        }
        return $variable;
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isArray($variable)
    {
        return static::is($variable, self::TYPE_ARRAY);
    }

    /**
     * @param mixed $variable
     * @param $type
     * @return bool
     */
    public static function is($variable, $type)
    {
        if (!in_array($type, [
            self::TYPE_ARRAY,
            self::TYPE_BOOLEAN,
            self::TYPE_CALLABLE,
            self::TYPE_DOUBLE,
            self::TYPE_FLOAT,
            self::TYPE_INT,
            self::TYPE_INTEGER,
            self::TYPE_LONG,
            self::TYPE_NULL,
            self::TYPE_NUMERIC,
            self::TYPE_OBJECT,
            self::TYPE_RESOURCE,
            self::TYPE_SCALAR,
            self::TYPE_STRING
        ])
        ) {
            return false;
        }
        switch ($type) {
            case self::TYPE_ARRAY: {
                return is_array($variable);
            }
            case self::TYPE_BOOLEAN: {
                return is_bool($variable);
            }
            case self::TYPE_CALLABLE: {
                return is_callable($variable);
            }
            case self::TYPE_DOUBLE: {
                return is_double($variable);
            }
            case self::TYPE_FLOAT: {
                return is_float($variable);
            }
            case self::TYPE_INT: {
                return is_int($variable);
            }
            case self::TYPE_INTEGER: {
                return is_integer($variable);
            }
            case self::TYPE_LONG: {
                return is_long($variable);
            }
            case self::TYPE_NULL: {
                return is_null($variable);
            }
            case self::TYPE_NUMERIC: {
                return is_numeric($variable);
            }
            case self::TYPE_OBJECT: {
                return is_object($variable);
            }

            case self::TYPE_RESOURCE: {
                return is_resource($variable);
            }
            case self::TYPE_SCALAR: {
                return is_scalar($variable);
            }
            case self::TYPE_STRING: {
                return is_string($variable);
            }
            default: {
                return false;
            }
        }
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isBoolean($variable)
    {
        return static::is($variable, self::TYPE_BOOLEAN);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isCallable($variable)
    {
        return static::is($variable, self::TYPE_CALLABLE);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isDouble($variable)
    {
        return static::is($variable, self::TYPE_DOUBLE);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isFloat($variable)
    {
        return static::is($variable, self::TYPE_FLOAT);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isInt($variable)
    {
        return static::is($variable, self::TYPE_INT);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isInteger($variable)
    {
        return static::is($variable, self::TYPE_INTEGER);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isLong($variable)
    {
        return static::is($variable, self::TYPE_LONG);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isNumeric($variable)
    {
        return static::is($variable, self::TYPE_NUMERIC);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isObject($variable)
    {
        return static::is($variable, self::TYPE_OBJECT);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isResource($variable)
    {
        return static::is($variable, self::TYPE_RESOURCE);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isScalar($variable)
    {
        return static::is($variable, self::TYPE_SCALAR);
    }

    /**
     * @param mixed $variable
     * @return bool
     */
    public static function isString($variable)
    {
        return static::is($variable, self::TYPE_STRING);
    }
}
