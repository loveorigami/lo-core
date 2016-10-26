<?php

/* @var $this yii\web\View */
/* @var $model \lo\core\modules\settings\models\KeyStorageItem */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Key Storage Item',
]) . ' ' . $model->key;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Key Storage Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->key, 'url' => ['view', 'id' => $model->key]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="key-storage-item-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
