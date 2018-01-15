<?php
/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Include Group',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Include Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
