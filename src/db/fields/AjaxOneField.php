<?php
namespace lo\core\db\fields;

use lo\core\grid\Select2Column;
use lo\core\inputs\Select2AjaxInput;
use yii\helpers\ArrayHelper;

/**
 * Class AjaxOneField
 * Поле для связей Has One. Интерфейс привязки в форме в виде выпадающего списка с ajax выбором.
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class AjaxOneField extends HasOneField
{

    public $inputClass = Select2AjaxInput::class;
    public $loadUrl;

    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();
        $grid["value"] = function ($model, $index, $widget) {
            return ArrayHelper::getValue($model, "{$this->relationName}.{$this->gridAttr}");
        };
        $grid["contentOptions"] = ['style'=>'width: 150px;'];
        $grid["initValueText"] = ArrayHelper::getValue($this->model, "{$this->relationName}.{$this->gridAttr}");
        $grid["class"] = Select2Column::class;
        $grid["loadUrl"] = $this->loadUrl;

        return $grid;
    }

}