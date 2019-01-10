<?php

namespace lo\core\helpers;

use Datetime;
use Exception;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class DateHelper
 *
 * @package lo\core\helpers
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class DateHelper
{
    public const TYPE_DATE = 'date';
    public const TYPE_TIME = 'time';
    public const TYPE_DATE_TIME = 'datetime';
    public const TYPE_OTHER = 'other';

    /** for database */
    public const DB_DATE_FORMAT = 'php:Y-m-d';
    public const DB_DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    public const DB_TIME_FORMAT = 'php:H:i:s';

    /** for datepicker */
    public const DP_DATE_FORMAT = 'yyyy-mm-dd';
    public const DP_DATETIME_FORMAT = 'yyyy-mm-dd hh:ii:ss';

    /** for views */
    public const UI_DATE_FORMAT = 'php:d.m.Y';
    public const UI_DATETIME_FORMAT = 'php:Y-m-d H:i:s';

    /**
     * @return string Y-m-d H:i:s
     * @throws InvalidConfigException
     */
    public static function nowDatetime(): string
    {
        return self::asDatetime('now', self::DB_DATETIME_FORMAT); // 2014-10-06
    }

    /**
     * @param $date
     * @return string
     * @throws InvalidConfigException
     */
    public static function datetime($date): string
    {
        return self::asDatetime($date, self::DB_DATETIME_FORMAT); // 2014-10-06
    }

    /**
     * @return string Y-m-d
     * @throws InvalidConfigException
     */
    public static function nowDate(): string
    {
        return self::asDate('now', self::DB_DATE_FORMAT); // 2014-10-06
    }

    /**
     * @param $date
     * @return string Y-m-d
     * @throws InvalidConfigException
     */
    public static function dbDate($date): string
    {
        return self::asDate($date, self::DB_DATE_FORMAT); // 2014-10-06
    }

    /**
     * @param null $date
     * @return string
     * @throws InvalidConfigException
     */
    public static function dmy($date = null): string
    {
        if (!$date) {
            $date = 'now';
        }

        return self::asDate($date, self::UI_DATE_FORMAT);
    }

    /**
     * @param null $date
     * @return string
     * @throws InvalidConfigException
     */
    public static function his($date = null): string
    {
        if (!$date) {
            $date = 'now';
        }

        return self::asDate($date, self::DB_TIME_FORMAT);
    }

    /**
     * @param $date_from
     * @param $date_to
     * @return int
     * @throws Exception
     */
    public static function rangeDays($date_from, $date_to): ?int
    {
        $datetime1 = new Datetime($date_from);
        $datetime2 = new Datetime($date_to);

        return (int)$datetime1->diff($datetime2)->days;
    }

    /**
     * @param        $date_from
     * @param        $date_to
     * @param string $operator
     * @return bool
     * @throws Exception
     */
    public static function compareDays($date_from, $date_to, $operator = '>'): bool
    {
        $datetime1 = new Datetime($date_from);
        $datetime2 = new Datetime($date_to);

        switch ($operator) {
            case '>=':
                $val = $datetime1 >= $datetime2;
                break;
            case '<':
                $val = $datetime1 < $datetime2;
                break;
            case '<=':
                $val = $datetime1 <= $datetime2;
                break;
            default:
                $val = $datetime1 > $datetime2;
        }

        return $val;
    }

    /**
     * @param $days
     * @param $date_from
     * @return string
     * @throws Exception
     */
    public static function rangeDateByDays($days, $date_from = null): string
    {
        $from = $date_from ?? self::nowDate();
        $date = new Datetime($from);
        $date->modify("$days days");

        return self::asDate($date, self::DB_DATE_FORMAT);
    }

    /**
     * @param $date
     * @return bool
     * @throws Exception
     */
    public static function moreToday($date): bool
    {
        $datetime1 = new Datetime($date);
        $datetime2 = new Datetime('now');

        return $datetime1 > $datetime2;
    }

    /**
     * @param $date
     * @return string
     * @throws InvalidConfigException
     */
    public static function prettyDate($date): string
    {
        return Yii::$app->formatter->asDate($date, 'long'); // 25 мая 2017
    }

    /**
     * @param $timestamp
     * @return bool
     */
    public static function isValidTimeStamp($timestamp): bool
    {
        return ((string)(int)$timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }

    /**
     * @param $timestamp
     * @return false|string
     */
    public static function path($timestamp): ?string
    {
        return date('Y/m', $timestamp);
    }

    /**
     * @param $timestamp
     * @return false|string
     */
    public static function pathFull($timestamp): ?string
    {
        return date('Y/m/d', $timestamp);
    }

    /**
     * @param $date
     * @param $format
     * @return string
     * @throws InvalidConfigException
     */
    protected static function asDate($date, $format): string
    {
        return Yii::$app->formatter->asDate($date, $format); // 2014-10-06
    }

    /**
     * @param $date
     * @param $format
     * @return string
     * @throws InvalidConfigException
     */
    protected static function asDatetime($date, $format): string
    {
        return Yii::$app->formatter->asDatetime($date, $format); // 2014-10-06 12:02:36
    }
}
