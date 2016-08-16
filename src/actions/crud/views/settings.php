<?php

use lo\core\components\settings\FormWidget;
use lo\core\db\ActiveRecord;

$this->title = Yii::t('backend', 'Application settings');

/** @var ActiveRecord $model */
echo FormWidget::widget([
    'model' => $model,
    'formClass' => '\yii\bootstrap\ActiveForm',
    'submitText' => Yii::t('backend', 'Save'),
    'submitOptions' => ['class' => 'btn btn-primary']
]);
