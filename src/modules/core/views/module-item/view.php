<?php
use lo\core\widgets\admin\CrudLinks;
use lo\core\widgets\admin\Detail;

/**
 * @var yii\web\View $this
 * @var common\models\Constraint $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('backend', 'Module Item'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-view">

    <p>
        <?= CrudLinks::widget(["action" => CrudLinks::CRUD_VIEW, "model" => $model]) ?>
    </p>

    <?= Detail::widget([
        'model' => $model,
    ]) ?>

</div>