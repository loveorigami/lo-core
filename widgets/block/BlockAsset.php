<?php

namespace lo\core\widgets\block;

use yii\web\AssetBundle;

/**
 * Class BlockAsset
 * Ассет виджета скрываемой области
 * @package lo\core\widgets\block
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class BlockAsset extends AssetBundle
{

    public $js = [
        'block.js',
    ];
    public $css = [
        'block.css',
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