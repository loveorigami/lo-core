<?php

namespace lo\core\widgets\yamap;

use yii\web\AssetBundle;

/**
 * Class YaMapInput
 * Ассет виджета выбора координат
 * @package lo\core\widgets\yamap
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class YaMapInputAsset extends AssetBundle
{

    public $js = [
        'yamap-input.js',
    ];

    public $jsMin = [
        'yamap-input.min.js',
    ];

    public $depends = [
		//'lo\core\components\uiboot\ThemeAsset',
		'yii\jui\JuiAsset',
		'lo\core\widgets\yamap\YaMapApiAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . "/assets";
        parent::init();
    }

}