<?php

use lo\core\widgets\admin\CrudLinks;
use lo\core\widgets\admin\Grid;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \lo\core\modules\i18n\models\I18nMessage $searchModel
 */

$this->title = Yii::t('backend', 'I18n Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <?php
    echo $this->render('/_menu');
    echo CrudLinks::widget(["action" => CrudLinks::CRUD_LIST, "model" => $searchModel]);
    echo $this->render('_filter', ['model' => $searchModel]);

    echo Grid::widget([
        'dataProvider' => $dataProvider,
        'model' => $searchModel,
    ]);
    ?>

</div>