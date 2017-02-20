<?php

namespace lo\core\helpers;

/**
 * Class DateHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DateHelper
{
    /**
     * @param null $date
     * @return false|string
     */
    public static function dmy($date = null)
    {
        $timestamp = self::timestamp($date);
        return date('d.m.y', $timestamp);
    }

    /**
     * @param null $date
     * @return false|int
     */
    public static function timestamp($date = null)
    {
        if (!$date) {
            $date = 'now';
        }
        return strtotime($date);
    }
}