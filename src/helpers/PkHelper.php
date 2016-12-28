<?php

namespace lo\core\helpers;

use lo\core\db\ActiveRecord;

/**
 * Class PkHelper
 * Хелпер для PrimaryKeys
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class PkHelper
{
    /**
     * @param ActiveRecord $model
     * @return null|string
     */
    public static function encode($model)
    {
        $keys = $model->primaryKey();
        if (count($keys) === 1) {
            return isset($model->attributes[$keys[0]]) ? $model->attributes[$keys[0]] : null;
        } else {
            $values = [];
            foreach ($keys as $name) {
                $values[$name] = isset($model->attributes[$name]) ? $model->attributes[$name] : null;
            }
            return base64_encode(serialize($values));
        }
    }

    /**
     * @param $pk
     * @return mixed
     */
    public static function decode($pk)
    {
        $int = filter_var($pk, FILTER_VALIDATE_INT);
        if (!$int) {
            return unserialize(base64_decode($pk));
        }
        return $pk;
    }

    /**
     * @param ActiveRecord|array $model
     * @return mixed
     */
    public static function keyEncode($model)
    {
        if ($model instanceof ActiveRecord) {
            $pk = $model->getPrimaryKey();
        } else {
            $pk = $model ? $model : null;
        }

        return base64_encode(serialize($pk));
    }

    /**
     * @param $pk
     * @return mixed
     */
    public static function keyDecode($pk)
    {
        return unserialize(base64_decode($pk));
    }
}