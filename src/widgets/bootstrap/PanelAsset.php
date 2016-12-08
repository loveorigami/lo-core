<?php

namespace lo\core\widgets\bootstrap;
use yii\web\AssetBundle;

/**
 * Class PanelAsset
 * @package lo\core\bootstrap\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class PanelAsset extends AssetBundle
{
    public $sourcePath = '@lo/core/widgets/bootstrap/assets';

    public $css = [
            'panel.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}
