<?php

namespace lo\core\widgets\yamap;

use yii\web\AssetBundle;

/**
 * Class YaMapApiInput
 * Ассет подключения API яндекс карт
 * @package lo\core\widgets\yamap
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class YaMapApiAsset extends AssetBundle
{

    public $js = [
        '//api-maps.yandex.ru/2.1/?lang=ru_RU',
    ];

    public $jsMin = [
        '//api-maps.yandex.ru/2.1/?lang=ru_RU',
    ];

}