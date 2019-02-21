<?php

/**
 * @var yii\web\View $this
 * @var \lo\core\modules\permission\models\Constraint $model
 */

$this->title = \Yii::t('common', 'Update Constraint') . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('common', 'Constraint'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('common', 'Update');
?>
<div class="constraint-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

