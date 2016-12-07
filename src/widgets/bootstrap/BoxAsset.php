<?php

namespace lo\core\widgets\bootstrap;
use yii\web\AssetBundle;

/**
 * Class BaseWidget
 * @package lo\core\bootstrap\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class BoxAsset extends AssetBundle
{
    public $sourcePath = '@lo/core/widgets/bootstrap/assets';

    public $js = [
            'box.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}
