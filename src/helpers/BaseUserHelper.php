<?php

namespace lo\core\helpers;

use lo\core\interfaces\IUser;

/**
 * Class UserHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class BaseUserHelper
{
    /**
     * @param IUser $user
     * @return bool
     */
    public static function name($user)
    {
        return $user->getName();
    }
}