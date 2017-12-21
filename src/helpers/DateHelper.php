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
    const TYPE_DATE = 'date';
    const TYPE_TIME = 'time';
    const TYPE_DATE_TIME = 'datetime';
    const TYPE_OTHER = 'other';

    /** for database */
    const DB_DATE_FORMAT = 'php:Y-m-d';
    const DB_DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const DB_TIME_FORMAT = 'php:H:i:s';

    /** for datapicker */
    const DP_DATE_FORMAT = 'yyyy-mm-dd';
    const DP_DATETIME_FORMAT = 'yyyy-mm-dd hh:ii:ss';

    /** for views */
    const UI_DATE_FORMAT = 'php:d.m.Y';
    const UI_DATETIME_FORMAT = 'php:Y-m-d H:i:s';

    /**
     * @return string Y-m-d H:i:s
     */
    public static function nowDatetime()
    {
        return self::asDatetime('now', self::DB_DATETIME_FORMAT); // 2014-10-06
    }

    /**
     * @param $date
     * @return string
     */
    public static function datetime($date)
    {
        return self::asDatetime($date, self::DB_DATETIME_FORMAT); // 2014-10-06
    }

    /**
     * @return string Y-m-d
     */
    public static function nowDate()
    {
        return self::asDate('now', self::DB_DATE_FORMAT); // 2014-10-06
    }

    /**
     * @param $date
     * @return string Y-m-d
     */
    public static function dbDate($date)
    {
        return self::asDate($date, self::DB_DATE_FORMAT); // 2014-10-06
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
        return self::asDate($date, self::UI_DATE_FORMAT);
    }

    /**
     * @param $date_from
     * @param $date_to
     * @return string
     */
    public static function rangeDays($date_from, $date_to)
    {
        $datetime1 = new \Datetime($date_from);
        $datetime2 = new \Datetime($date_to);

        return $datetime1->diff($datetime2)->days;
    }

    /**
     * @param $date_from
     * @param $date_to
     * @return bool
     */
    public static function compareDays($date_from, $date_to)
    {
        $datetime1 = new \Datetime($date_from);
        $datetime2 = new \Datetime($date_to);

        return $datetime1 > $datetime2;
    }

    /**
     * @param $days
     * @param $date_from
     * @return mixed
     */
    public static function rangeDateByDays($days, $date_from = null)
    {
        $from = $date_from ?? self::nowDate();
        $date = new \Datetime($from);
        $date->modify("$days days");

        return self::asDate($date, self::DB_DATE_FORMAT);
    }

    /**
     * @param $date
     * @return bool
     */
    public static function moreToday($date): bool
    {
        $datetime1 = new \Datetime($date);
        $datetime2 = new \Datetime("now");

        return $datetime1 > $datetime2;
    }

    /**
     * @param $date
     * @return string
     */
    public static function prettyDate($date)
    {
        return Yii::$app->formatter->asDate($date, 'long'); // 25 мая 2017
    }

    /**
     * @param $timestamp
     * @return bool
     */
    public static function isValidTimeStamp($timestamp)
    {
        return ((string)(int)$timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }

    /**
     * @param $date
     * @param $format
     * @return string
     */
    protected static function asDate($date, $format)
    {
        return Yii::$app->formatter->asDate($date, $format); // 2014-10-06
    }

    /**
     * @param $date
     * @param $format
     * @return string
     */
    protected static function asDatetime($date, $format)
    {
        return Yii::$app->formatter->asDatetime($date, $format); // 2014-10-06 12:02:36
    }
}