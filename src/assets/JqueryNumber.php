<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:40 AM
 */

namespace lo\core\assets;


use yii\web\AssetBundle;

class JqueryNumber extends AssetBundle{
    public $sourcePath = '@vendor/bower/jquery-number';
    public $js = [
        'jquery.number.js'
    ];
    public $depends = [
        '\yii\web\JqueryAsset',
    ];
}