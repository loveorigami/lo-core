<?php
/**
 * @var yii\web\View $this
 * @var lo\core\modules\core\models\Template $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */
use lo\core\widgets\admin\Grid;
use lo\core\widgets\admin\CrudLinks;

$this->title = Yii::t('backend', 'Template');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-index">
    <?= CrudLinks::widget(["action" => CrudLinks::CRUD_LIST, "model" => $searchModel]) ?>
    <?= $this->render('_filter', ['model' => $searchModel]); ?>

    <?= Grid::widget([
        'dataProvider' => $dataProvider,
        'model' => $searchModel,
    ]);
    ?>

</div>
