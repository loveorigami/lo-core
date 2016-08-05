<?php
namespace lo\core\assets;

use yii\web\AssetBundle;

class JqueryNumber extends AssetBundle
{
    public $sourcePath = '@vendor/bower/jquery-number';
    public $js = [
        'jquery.number.js'
    ];
    public $depends = [
        '\yii\web\JqueryAsset',
    ];
}