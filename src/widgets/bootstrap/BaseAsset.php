<?php

namespace lo\core\widgets\bootstrap;
use yii\web\AssetBundle;

/**
 * Class BaseWidget
 * @package lo\core\bootstrap\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class BaseAsset extends AssetBundle
{
    public $sourcePath = '@lo/core/widgets/bootstrap/assets';

    public $js = [
            'admlteext.js',
    ];

    public $css = [
            'admlteext.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}
