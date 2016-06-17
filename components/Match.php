<?php
namespace lo\core\components;

use Yii;
use yii\base\Object;

/**
 * Class Match
 * Базовый класс проверки условий
 * @package lo\core\components
 * @author Churkin Anton <webadmin87@gmail.com>
 */
abstract class Match extends Object
{

    /**
     * Константы условий подключения шаблонов
     */

    const COND_NO = 0;

    const COND_URL = 1;

    const COND_PHP = 2;

    const COND_ROUTE = 3;

    /**
     * Возвращает объект для проверки условия подключения шаблона. False в случае ошибки
     * @param int $type тип условия для которого необходимо создать компонент
     * @return Match
     */

    public static function getMatch($type)
    {

        if ($type == self::COND_PHP)
            return Yii::createObject(PhpMatch::class);
        elseif ($type == self::COND_URL)
            return Yii::createObject(UrlMatch::class);
        elseif ($type == self::COND_ROUTE)
            return Yii::createObject(RouteMatch::class);
        else
            return false;
    }

    /**
     * @param mixed $value
     * @return boolean
     */

    abstract public function test($value);

}