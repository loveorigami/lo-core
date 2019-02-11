<?php

namespace lo\core\url;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\web\UrlManager;

class BackendUrlHelper
{
    /**
     * @return UrlManager
     * @throws InvalidConfigException
     */
    protected static function container(): UrlManager
    {
        /** @var UrlManager $obj */
        $obj = Yii::$app->get('backendUrlManager');

        return $obj;
    }

    /**
     * @param $route
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function url($route)
    {
        $url = self::container();
        $link = $url->createAbsoluteUrl($route, true);

        return Url::to($link);
    }

}
