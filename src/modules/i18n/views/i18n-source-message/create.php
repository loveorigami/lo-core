<?php
/**
 * @var  yii\web\View $this
 * @var \lo\core\modules\i18n\models\I18nSourceMessage $model
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'I18n Source Message',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'I18n Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-create">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
