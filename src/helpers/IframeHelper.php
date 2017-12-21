<?php

namespace lo\core\helpers;

use lo\widgets\colorbox\Colorbox;
use yii\helpers\Html;

/**
 * Class BS
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class IframeHelper
{
    /**
     * Bootstrap CSS helpers
     */
    const IFRAME_SELECTOR = 'iframe-page';
    const QUERY_PARAM = 'iframe';

    /**
     * @param $params
     * @return string
     */
    public static function register($params = [])
    {
        $params = ArrayHelper::merge([
            'reload' => true,
            'selector' => '.' . self::IFRAME_SELECTOR,
            'clientOptions' => [
                'width' => '95%',
                'height' => '90%',
                'iframe' => true,
                'fixed' => true,
            ],
        ], $params);

        return Colorbox::widget($params);
    }

    /**
     * @param $text
     * @param null $url
     * @param array $options
     * @return string
     */
    public static function a($text, $url = null, $options = [])
    {
        if ($url !== null) {
            ArrayHelper::setValue($url, self::QUERY_PARAM, 1);
        }
        return Html::a($text, $url, $options);
    }
}