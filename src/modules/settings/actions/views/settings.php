<?php

use lo\core\modules\settings\widgets\FormWidget;

$this->title = Yii::t('backend', 'Application settings');

/** @var \lo\core\modules\settings\models\FormModel $model */
echo FormWidget::widget([
    'model' => $model,
    'formClass' => '\yii\bootstrap\ActiveForm',
    'submitText' => Yii::t('backend', 'Save'),
    'submitOptions' => ['class' => 'btn btn-primary']
]);
