<?php

namespace lo\core\helpers;

use lo\core\interfaces\UserInterface;
use Yii;

/**
 * Class UserHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class BaseUserHelper
{
    const DEFAULT_USER = 1;

    /**
     * @param UserInterface $user
     * @return bool
     */
    public static function name($user)
    {
        return $user->getName();
    }

    /**
     * @return bool
     */
    public static function isGuest()
    {
        return Yii::$app->user->isGuest;
    }

    /**
     * @return int
     */
    public static function id()
    {
        if (self::isGuest()) {
            return self::DEFAULT_USER;
        } else {
            return Yii::$app->user->identity->id;
        }
    }
}