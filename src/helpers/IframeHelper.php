<?php

namespace lo\core\helpers;

use lo\widgets\colorbox\Colorbox;
use yii\helpers\Html;

/**
 * Class BS
 *
 * @package lo\core\helpers
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class IframeHelper
{
    /**
     * Bootstrap CSS helpers
     */
    public const IFRAME_SELECTOR = 'iframe-page';
    public const QUERY_PARAM = 'iframe';

    /**
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public static function register($params = []): string
    {
        $params = ArrayHelper::merge(self::colorboxDefaultParams(), $params);

        return Colorbox::widget($params);
    }

    /**
     * @return string
     */
    public static function colorboxJsonParams(): string
    {
        return JsonHelper::encode(self::colorboxDefaultParams());
    }

    /**
     * @return array
     */
    protected static function colorboxDefaultParams(): array
    {
        return [
            'reload' => true,
            'selector' => '.' . self::IFRAME_SELECTOR,
            'clientOptions' => [
                'width' => '95%',
                'height' => '90%',
                'iframe' => true,
                'fixed' => true,
            ],
        ];
    }

    /**
     * @param       $text
     * @param null  $url
     * @param array $options
     * @return string
     */
    public static function a($text, $url = null, $options = []): string
    {
        if ($url !== null) {
            $url = self::url($url);
        }

        return Html::a($text, $url, $options);
    }

    /**
     * @param $url
     * @return string
     */
    public static function url($url): string
    {
        if (is_array($url)) {
            ArrayHelper::setValue($url, self::QUERY_PARAM, '1');
        } elseif ($url !== null) {
            $url .= '&' . self::QUERY_PARAM . '=1';
        }

        return $url;
    }
}
