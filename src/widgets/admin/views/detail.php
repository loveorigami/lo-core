<?php
use yii\widgets\DetailView;

/**
 * @var \lo\core\db\ActiveRecord $model модель
 * @var array $attributes массив описанй атрибутов
 * @var string $id идентификатор виджета
 * @var \yii\web\View $this
 */

echo DetailView::widget([
    'model' => $model,
    'attributes' => $attributes,
]);