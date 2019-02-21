<?php

/**
 * @var $this yii\web\View
 * @var \lo\core\modules\main\models\IncludeGroup $model
 */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Include Group',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Include Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="group-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
