<?php
/**
 * @var  yii\web\View $this
 * @var \lo\core\modules\i18n\models\I18nSourceMessage $model
 */

use lo\core\widgets\admin\CrudLinks;
use lo\core\widgets\admin\Detail;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'I18n Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-view">

    <?php
    echo CrudLinks::widget([
        "action" => CrudLinks::CRUD_VIEW,
        "model" => $model
    ]);

    echo Detail::widget([
        'model' => $model,
    ]);
    ?>

</div>
