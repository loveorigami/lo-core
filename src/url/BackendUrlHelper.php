<?php

namespace lo\core\url;

use Yii;
use yii\helpers\Url;
use yii\web\UrlManager;

class BackendUrlHelper
{
    /**
     * @return UrlManager
     */
    protected static function container()
    {
        /** @var UrlManager $obj */
        $obj = Yii::$app->get('backendUrlManager');
        return $obj;
    }

    /**
     * @param $route
     * @return mixed
     */
    public static function url($route)
    {
        $url = self::container();
        $link = $url->createAbsoluteUrl($route);
        return Url::to($link);
    }

}