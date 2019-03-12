<?php

namespace lo\core\grid;

use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class XEditableColumn
 * Исправление багов расширения
 *
 * @package lo\core\xeditable
 */
class UpdateColumn extends DataColumn
{
    public $route;

    /**
     * @inheritdoc
     */
    protected function getDataCellContent($model, $key, $index)
    {
        if (empty($this->route)) {
            $this->route = \Yii::$app->urlManager->createUrl($_SERVER['REQUEST_URI']);
        }

        if (empty($this->value)) {
            $value = ArrayHelper::getValue($model, $this->attribute);
        } else {
            $value = call_user_func($this->value, $model, $index, $this);
        }

        $value = Html::a(
            $value,
            Url::to([$this->route, 'id' => $key]),
            [
                'data-pjax' => 0,
                'class' => 'text-primary',
            ]);

        return $value;
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return $this->getDataCellContent($model, $key, $index);
    }

} 
