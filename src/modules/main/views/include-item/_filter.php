<?php
use lo\core\widgets\admin\ExtFilter;

/**
 * @var yii\web\View $this
 * @var \lo\core\modules\main\models\IncludeItem $model
 */

echo ExtFilter::widget(["model" => $model]);