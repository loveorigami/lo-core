<?php

use lo\core\widgets\admin\Grid;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\Constraint $searchModel
 */

echo Grid::widget([
    'dataProvider' => $dataProvider,
    'model' => $searchModel,
]);

?>