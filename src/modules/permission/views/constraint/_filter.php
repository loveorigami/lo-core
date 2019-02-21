<?php
use lo\core\widgets\admin\ExtFilter;

/**
 * @var yii\web\View $this
 * @var \lo\core\modules\permission\models\Constraint $model
 */

echo ExtFilter::widget(["model" => $model]);