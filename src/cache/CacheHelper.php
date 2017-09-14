<?php

namespace lo\core\cache;

use Yii;

/**
 * Class CacheHelper
 * Содержит методы для работы с кешем
 * @package lo\core\helpers
 * @author Andrey Lukyanov
 */
class CacheHelper
{
    const DEFAULT = 'cache';
    const FRONTEND = 'cacheFrontend';
    const COMMON = 'cacheCommon';
    const BACKEND = 'cacheBackend';

    /**
     * Возвращает идентификатор кеше для действия контроллера
     * @param string $id базовый идентификатор кеша
     * @return array
     */
    public static function getActionCacheId($id)
    {
        return [$id, Yii::$app->request->url];
    }
}