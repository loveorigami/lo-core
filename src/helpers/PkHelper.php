<?php

namespace lo\core\helpers;

use lo\core\db\ActiveRecord;

/**
 * Class PkHelper
 * Хелпер для PrimaryKeys
 *
 * @package lo\core\helpers
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class PkHelper
{
    /**
     * @param ActiveRecord $model
     * @return null|string
     */
    public static function encode($model): ?string
    {
        $keys = $model::primaryKey();

        if (\count($keys) === 1) {
            return $model->attributes[$keys[0]] ?? null;
        }

        $values = [];
        foreach ($keys as $name) {
            $values[$name] = $model->attributes[$name] ?? null;
        }

        return base64_encode(serialize($values));
    }

    /**
     * @param $pk
     * @return mixed
     */
    public static function decode($pk)
    {
        $int = filter_var($pk, FILTER_VALIDATE_INT);
        if (!$int) {
            return unserialize(base64_decode($pk), ['allowed_classes' => false]);
        }

        return $pk;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function decodeAll(array $data): array
    {
        $result = [];
        foreach ($data as $item) {
            $result[] = self::decode($item);
        }

        return $result;
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
            $pk = $model ?: null;
        }

        return base64_encode(serialize($pk));
    }

    /**
     * @param $pk
     * @return mixed
     */
    public static function keyDecode($pk)
    {
        return unserialize(base64_decode($pk), ['allowed_classes' => false]);
    }
}
