<?php
namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class AjaxOneField
 * Поле для связей Has One. Интерфейс привязки в форме в виде выпадающего списка с ajax выбором.
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class AjaxOneField extends HasOneField
{

    public $inputClass = '\lo\core\inputs\Select2AjaxInput';

    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();
        $grid["value"] = function ($model, $index, $widget) {
            return ArrayHelper::getValue($model, "{$this->relation}.{$this->gridAttr}");
        };
        $grid["contentOptions"] = ['style'=>'width: 150px;'];
        $grid["initValueText"] = ArrayHelper::getValue($this->model, "{$this->relation}.{$this->gridAttr}");
        $grid["class"] = \lo\core\grid\Select2Column::className();

        return $grid;
    }

}