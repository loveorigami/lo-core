<?php
use lo\core\widgets\admin\Detail;
use lo\core\widgets\admin\CrudLinks;

/**
 * @var yii\web\View $this
 * @var \lo\core\modules\main\models\IncludeItem $model
 */

$this->title = Yii::t('backend', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Include Item'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-view">

    <?= CrudLinks::widget(["action" => CrudLinks::CRUD_VIEW, "model" => $model]) ?>

    <?= Detail::widget([
        'model' => $model,
    ]) ?>

</div>