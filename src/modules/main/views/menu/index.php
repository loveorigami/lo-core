<?php
use lo\core\widgets\admin\CrudLinks;
use lo\core\widgets\admin\Grid;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use voskobovich\tree\manager\widgets\nestable\Nestable;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var lo\modules\main\models\Menu $searchModel
 * @var int $parent_id
 */

$this->title = \Yii::t('backend', 'Menu');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="menu-index">

    <?= CrudLinks::widget(["action" => CrudLinks::CRUD_LIST, "model" => $searchModel, "urlParams" => ["parent_id" => $parent_id]]) ?>
    <?= $this->render('_filter', ['model' => $searchModel]); ?>

    <?= Breadcrumbs::widget([
        'homeLink' => [
            "label" => \Yii::t('backend', 'Root'),
            "url" => ["/" . Yii::$app->controller->route]
        ],
        'links' => $searchModel->getBreadCrumbsItems($parent_id, function ($model) {
            return ["/" . Yii::$app->controller->route, "parent_id" => $model->id];
        }),
    ]) ?>

    <?php

    echo Grid::widget([
        'dataProvider' => $dataProvider,
        'model' => $searchModel,
        'tree' => true,
    ]);
    ?>

</div>
