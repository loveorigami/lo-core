<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \lo\core\db\ActiveRecord $model модель
 * @var array fields массив полей модели
 * @var \yii\web\View $this
 * @var string $id идентификатор виджета
 * @var array $formOptions параметры \yii\widgets\ActiveForm
 * @var integer $cols количество колонок в фильтре
 */

$cls = 12 / $cols;

$searchBtnName = $id . "-search";

?>

<?= Html::button(Yii::t('core', 'Extended search'), ['class' => 'btn btn-default pull-right', 'onClick' => '$("#' . $id . '").toggle()']) ?>

<div class="clear_big"></div>

<div id="<?= $id ?>" <? if (Yii::$app->request->get($searchBtnName) === null): ?>style="display: none;"<? endif ?>
     class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin($formOptions); ?>

        <? for ($i = 0; $i < count($fields); $i += $cols): ?>

            <div class="row">
                <? for ($j = $i; $j < $i + $cols; $j++): ?>

                    <? if (isset($fields[$j]) AND $fields[$j]->showInExtendedFilter): ?>

                        <div class="col-xs-12 col-sm-<?= $cls ?> col-md-<?= $cls ?> col-lg-<?= $cls ?>">
                            <?= $fields[$j]->extendedFilterForm($form) ?>
                        </div>

                    <? endif; ?>

                <? endfor; ?>

            </div>

        <? endfor; ?>


            <?= Html::submitButton(Yii::t('core', 'Search'), ['name' => $searchBtnName, 'class' => 'btn btn-primary']) ?>


        <? ActiveForm::end(); ?>
    </div>
</div>