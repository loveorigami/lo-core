<?php
use lo\core\widgets\admin\ExtFilter;

/**
 * @var yii\web\View $this
 * @var app\modules\main\models\Menu $model
 */

echo ExtFilter::widget(["model" => $model]);