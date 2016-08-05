<?php

namespace lo\core\widgets\translit;

use yii\web\AssetBundle;

/**
 * Class TranslitInputAsset
 * @package lo\core\widgets\translit
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TranslitInputAsset extends AssetBundle
{

    public $js = [
        'translit.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . "/assets";
        parent::init();
    }

}