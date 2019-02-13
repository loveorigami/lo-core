<?php

namespace lo\core\url;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UrlManager;

/**
 * Class FrontendUrlHelper
 *
 * @package lo\core\url
 * @author  Lukyanov Andrey <loveorigami@mail.ru>\
 */
class FrontendUrlHelper
{
    /**
     * @return UrlManager
     * @throws InvalidConfigException
     */
    protected static function container(): UrlManager
    {
        /** @var UrlManager $obj */
        $obj = Yii::$app->get('frontendUrlManager');

        return $obj;
    }

    /**
     * @param      $route
     * @param null $base_url
     * @return string
     * @throws InvalidConfigException
     */
    public static function url($route, $base_url = null): string
    {
        $url = self::container();

        if ($base_url) {
            $url->setHostInfo($base_url);
        }

        $link = $url->createAbsoluteUrl($route);

        return is_array($route) ? Url::to($link) : $route;
    }

    /**
     * @param $route
     * @return string
     * @throws InvalidConfigException
     */
    public static function toSiteBtn($route): string
    {
        return Html::a(Yii::t('core', 'On site'),
            self::url($route), [
                'target' => '_blank',
                'class' => 'btn btn-danger pull-right',
            ]
        );
    }
}
