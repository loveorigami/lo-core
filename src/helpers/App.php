<?php

namespace lo\core\helpers;

use Yii;
use yii\base\Module;
use yii\helpers\ArrayHelper;

class App
{
    /**
     * @return null|object
     */
    public static function user()
    {
        return Yii::$app->get('user');
    }

    /**
     * @return null|object
     */
    public static function cache()
    {
        return Yii::$app->get('cache');
    }

    /**
     * @param string $moduleName
     * @return Module|null the module instance, null if the module does not exist.
     */
    public static function getModule($moduleName)
    {
        return Yii::$app->getModule($moduleName);
    }

    /**
     * @param $message
     * @param array $params
     * @param string $category
     * @return string
     */
    public static function t($message, $params = [], $category = 'backend')
    {
        return Yii::t($category, $message, $params);
    }

    /**
     * @param $params
     * @param bool $isAbsolute
     * @param string $componentName
     * @return mixed
     */
    public static function url($params, $isAbsolute = false, $componentName = 'urlManager')
    {
        if ($isAbsolute) {
            return Yii::$app->get($componentName)->createAbsoluteUrl($params);
        } else {
            return Yii::$app->get($componentName)->createUrl($params);
        }
    }

    /**
     * @param $paramName
     * @return mixed
     */
    public static function params($paramName)
    {
        return ArrayHelper::getValue(Yii::$app->params, $paramName);
    }

    /**
     * @return \yii\console\Request|\yii\web\Request
     */
    public static function request()
    {
        return Yii::$app->getRequest();
    }

    /**
     * @return \yii\db\Connection
     */
    public static function db()
    {
        return Yii::$app->get('db');
    }

    /**
     * @return \yii\web\Session
     */
    public static function session()
    {
        return Yii::$app->getSession();
    }

    /**
     * @return \yii\mail\BaseMailer
     */
    public static function mail()
    {
        return Yii::$app->get('mail');
    }

    /**
     * @return \yii\i18n\Formatter
     */
    public static function formatter()
    {
        return Yii::$app->get('formatter');
    }

    /**
     * @param string $componentName
     * @return \yii\rbac\DbManager
     */
    public static function authManager($componentName = 'authManager')
    {
        return Yii::$app->get($componentName);
    }

    /**
     * Triggers event
     * @param $eventName
     * @param $event
     */
    public static function trigger($eventName, $event = null)
    {
        Yii::$app->trigger($eventName, $event);
    }

    /**
     * @return \yii\base\Security
     */
    public static function security()
    {
        return Yii::$app->security;
    }
}
