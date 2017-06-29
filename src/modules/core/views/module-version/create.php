<?php
/* @var $this yii\web\View */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Module Version',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Module Version'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-create">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
