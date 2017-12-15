<?php

namespace lo\core\helpers;

/**
 * Class YiiHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class YiiHelper
{
    const FLASH_ERROR = 'error';

    /**
     * @param $type
     * @param $message
     */
    public static function flash($type, $message)
    {
        return App::session()->setFlash($type, $message);
    }

    /**
     * @param $message
     */
    public static function flashError($message)
    {
        return self::flash(self::FLASH_ERROR, $message);
    }
}