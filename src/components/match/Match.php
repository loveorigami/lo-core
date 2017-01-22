<?php
namespace lo\core\components\match;

use Yii;
use yii\base\Object;

/**
 * Class Match
 * Базовый класс проверки условий
 * @package lo\core\components
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class Match extends Object
{
    /** Константы условий подключения шаблонов */
    const COND_NO = 0;
    const COND_URL = 1;
    const COND_PHP = 2;
    const COND_ROUTE = 3;

    /**
     * Возвращает объект для проверки условия подключения шаблона. False в случае ошибки
     * @param int $type тип условия для которого необходимо создать компонент
     * @return Match|boolean
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
    abstract public function test($value);

}