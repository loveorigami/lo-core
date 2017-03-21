<?php

namespace lo\core\helpers;

use lo\core\interfaces\IUser;
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
     * @param IUser $user
     * @return bool
     */
    public static function name($user)
    {
        return $user->getName();
    }

    /**
     * @return int
     */
    public static function id()
    {
        if (Yii::$app->user->isGuest) {
            return self::DEFAULT_USER;
        } else {
            return Yii::$app->user->identity->id;
        }
    }
}