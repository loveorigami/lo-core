<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Constraint $model
 */

$this->title = \Yii::t('backend', 'Update Constraint') . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('backend', 'Template'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('backend', 'Update');
?>
<div class="template-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

