<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 13.07.2016
 * Time: 8:40
 */

namespace lo\core\widgets\awcheckbox;

use yii\web\AssetBundle;
class AwesomeCheckboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/awesome-bootstrap-checkbox';
    public $css = [
        'awesome-bootstrap-checkbox.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}