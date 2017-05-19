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

<div id="<?= $id ?>" <?php if (Yii::$app->request->get($searchBtnName) === null): ?>style="display: none;"<?php endif; ?>
     class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin($formOptions); ?>

        <?php for ($i = 0; $i < count($fields); $i += $cols): ?>

            <div class="row">
                <?php for ($j = $i; $j < $i + $cols; $j++): ?>

                    <?php if (isset($fields[$j]) AND $fields[$j]->showInExtendedFilter): ?>

                        <div class="col-xs-12 col-sm-<?= $cls ?> col-md-<?= $cls ?> col-lg-<?= $cls ?>">
                            <?= $fields[$j]->getExtendedFilterForm($form) ?>
                        </div>

                    <?php endif; ?>

                <?php endfor; ?>

            </div>

        <?php endfor; ?>


            <?= Html::submitButton(Yii::t('core', 'Search'), ['name' => $searchBtnName, 'class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('core', 'Reset'), ["/".Yii::$app->controller->route],['class' => 'btn btn-default']) ?>
            <?= Html::hiddenInput('extendedFilter', 1)?>

        <?php ActiveForm::end(); ?>
    </div>
</div>