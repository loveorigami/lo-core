<?php

namespace lo\core\helpers;
use Yii;

/**
 * Class DateHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DateHelper
{
    const TYPE_DATE      = 'date';
    const TYPE_TIME      = 'time';
    const TYPE_DATE_TIME = 'datetime';
    const TYPE_OTHER     = 'other';

    /** for database */
    const DB_DATE_FORMAT     = 'php:Y-m-d';
    const DB_DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const DB_TIME_FORMAT     = 'php:H:i:s';

    /** for datapicker */
    const DP_DATE_FORMAT = 'php:d.m.Y';

    /**
     * @return string Y-m-d H:i:s
     */
    public static function nowDatetime()
    {
        return self::asDatetime('now', self::DB_DATETIME_FORMAT); // 2014-10-06
    }

    /**
     * @return string Y-m-d
     */
    public static function nowDate()
    {
        return self::asDate('now', self::DB_DATE_FORMAT); // 2014-10-06
    }

    /**
     * @param null $date
     * @return false|string
     */
    public static function dmy($date = null)
    {
        if (!$date) {
            $date = 'now';
        }
        return self::asDate($date, self::DP_DATE_FORMAT);
    }

    /**
     * @param $date
     * @param $format
     * @return string
     */
    protected static function asDate($date, $format){
        return Yii::$app->formatter->asDate($date, $format); // 2014-10-06
    }

    /**
     * @param $date
     * @param $format
     * @return string
     */
    protected static function asDatetime($date, $format){
        return Yii::$app->formatter->asDatetime($date, $format); // 2014-10-06 12:02:36
    }
}