<?php
use lo\core\widgets\admin\ExtFilter;

/**
 * @var yii\web\View $this
 * @var \lo\core\modules\i18n\models\I18nSourceMessage $model
 */

echo ExtFilter::widget(["model" => $model]);