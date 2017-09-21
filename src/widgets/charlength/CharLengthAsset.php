<?php

namespace lo\core\widgets\charlength;

use yii\web\AssetBundle;

/**
 * Class MaxLengthAsset
 * @package lo\core\widgets\block
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CharLengthAsset extends AssetBundle
{

    public $js = [
        'charlength.js',
    ];
    public $css = [
        'charlength.css',
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