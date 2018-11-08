<?php

namespace lo\core\components\match;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * Class Match
 * Базовый класс проверки условий
 *
 * @package lo\core\components
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class Match extends BaseObject
{
    /** Константы условий подключения шаблонов */
    public const COND_NO = 0;
    public const COND_URL = 1;
    public const COND_PHP = 2;
    public const COND_ROUTE = 3;

    /**
     * Возвращает объект для проверки условия подключения шаблона. False в случае ошибки
     *
     * @param int $type тип условия для которого необходимо создать компонент
     * @return Match|boolean
     * @throws InvalidConfigException
     */
    public static function getMatch($type)
    {
        switch ($type) {
            case self::COND_PHP;
                return Yii::createObject(PhpMatch::class);
                break;
            case self::COND_URL;
                return Yii::createObject(UrlMatch::class);
                break;
            case self::COND_ROUTE;
                return Yii::createObject(RouteMatch::class);
                break;
            default:
                return false;
        }
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    abstract public function test($value): bool;

}
