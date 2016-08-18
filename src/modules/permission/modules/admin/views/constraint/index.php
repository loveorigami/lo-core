<?php
use lo\core\widgets\admin\CrudLinks;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var Constraint $searchModel
 */

$this->title = \Yii::t('common', 'Constraint');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="constraint-index">

    <?= $this->render('_filter', ['model' => $searchModel]); ?>

    <hr/>

    <p>
        <?= CrudLinks::widget(["action" => CrudLinks::CRUD_LIST, "model" => $searchModel]) ?>
    </p>

    <?= $this->render('_grid', ['dataProvider' => $dataProvider, "searchModel" => $searchModel]); ?>


</div>
