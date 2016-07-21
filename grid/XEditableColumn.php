<?php

namespace lo\core\grid;

use mcms\xeditable\XEditableColumn as Base;
use yii\db\ActiveRecord;
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

        $value = '<a href="#" data-name="' . $this->attribute . '" data-value="' . Html::encode($model->{$this->attribute}) . '"  class="editable" data-type="' . $this->dataType . '" data-pk="' . $this->getPkValue($model) . '" data-url="' . $this->url . '" data-title="' . $this->dataTitle . '">' . $this->grid->formatter->format($value, $this->format) . '</a>';

        return $value;
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return $this->getDataCellContent($model, $key, $index);
    }

    /**
     * @param ActiveRecord $model
     * @return string
     */
    protected function getPkValue($model)
    {
        $pk = $model ? $model->getPrimaryKey() : null;
        return base64_encode(serialize($pk));
    }
} 