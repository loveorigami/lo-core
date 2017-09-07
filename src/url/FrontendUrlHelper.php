<?php

namespace lo\core\url;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UrlManager;

class FrontendUrlHelper
{
    /**
     * @return UrlManager
     */
    protected static function container()
    {
        /** @var UrlManager $obj */
        $obj = Yii::$app->get('frontendUrlManager');
        return $obj;
    }

    /**
     * @param $route
     * @param null $scheme
     * @return string
     */
    public static function url($route, $scheme = null): string
    {
        $url = self::container();
        $link = $url->createAbsoluteUrl($route);
        return Url::to($link, $scheme);
    }

    /**
     * @param $route
     * @return string
     */
    public static function toSiteBtn($route): string
    {
        return Html::a(Yii::t('core', 'On site'),
            self::url($route), [
                'target' => '_blank',
                'class' => 'btn btn-danger pull-right'
            ]
        );
    }
}