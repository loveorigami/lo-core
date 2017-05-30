<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var string $id идентификатор виджета
 * @var string $pjaxId идентификатор виджета PJAX
 * @var \yii\data\ActiveDataProvider $dataProvider провайдер данных
 * @var \lo\core\db\ActiveRecord $model модель
 * @var array $columns массив с описанием полей таблицы
 * @var array $groupButtons массив с описанием кнопок груповых операций
 * @var \yii\web\View $this
 */

?>
<?php Pjax::begin(["id" => $pjaxId]); ?>
<?= Html::beginForm(); ?>
<?php
echo GridView::widget([
    "id" => $id,
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'columns' => $columns,
]);
?>

<?php
$btnHtml = null;
foreach ($groupButtons AS $button): ?>
    <?php if (is_array($button)):
        $widget = Yii::createObject($button);
        ?>
        <?php $btnHtml .= $widget->run() . "\n"; ?>
    <?php endif; ?>
<?php endforeach; ?>

<?php if ($btnHtml): ?>
    <div class="form-group form-inline">

        <div><?= Yii::t('core', 'Actions with selected') ?>:</div>

        <?= $btnHtml ?>

    </div>
<?php endif; ?>

<?= Html::endForm(); ?>
<?php Pjax::end(); ?>