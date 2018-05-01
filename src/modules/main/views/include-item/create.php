<?php
/**
 * @var $this yii\web\View
 * @var \lo\core\modules\main\models\IncludeItem $model
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Include Item',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Include Item'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-create">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
