<?php

namespace lo\core\grid;

use lo\core\helpers\PkHelper;
use mcms\xeditable\XEditableColumn as Base;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class XEditableColumn
 * Исправление багов расширения
 * @package lo\core\xeditable
 */
class XEditableColumn extends Base
{
    public $editable = null;

    /**
     * @inheritdoc
     */
    protected function getDataCellContent($model, $key, $index)
    {
        if (empty($this->url)) {
            $this->url = \Yii::$app->urlManager->createUrl($_SERVER['REQUEST_URI']);
        }

        if (empty($this->value)) {
            $value = ArrayHelper::getValue($model, $this->attribute);
        } else {
            $value = call_user_func($this->value, $model, $index, $this);
        }

        $value = Html::a($this->grid->formatter->format($value, $this->format), '#', [
            'data' => [
                'name' => $this->attribute,
                'value' => Html::encode($model->{$this->attribute}),
                'type' => $this->dataType,
                'pk' => PkHelper::keyEncode($model),
                'url' => $this->url,
                'title' => $this->dataTitle,
            ],
            'class' => 'editable',
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