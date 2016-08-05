<?php

namespace lo\core\widgets\offcanvas;

use yii\web\AssetBundle;

/**
 * Class OffcanvasAsset
 * Ассет виджета скрываемой области
 * @package lo\core\widgets\offcanvas
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class OffcanvasAsset extends AssetBundle
{

    public $js = [
        'offcanvas.js',
    ];
    public $css = [
        'offcanvas.css',
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