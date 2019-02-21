<?php

/**
 * @var \yii\web\View $this
 * @var common\models\Constraint $model
 */

$this->title = \Yii::t('common', 'Create Constraint');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('common', 'Constraint'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="constraint-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
