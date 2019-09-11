<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveQuery;
use lo\core\helpers\Html;
use lo\core\inputs\ColorInput;

/**
 * Class ColorField
 *
 * @package lo\core\db\fields
 */
class ColorField extends TextField
{
    public $inputClass = ColorInput::class;

    /**
     * Конфигурация поля для грида (GridView)
     *
     * @return array
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();

        $grid["format"] = 'raw';
        $grid['headerOptions'] = [
            'style' => 'width: 50 px;',
        ];
        $grid["value"] = function ($model) {
            return $this->getGridValue($model);
        };

        return $grid;
    }

    /**
     * @inheritdoc
     */
    protected function defaultGridFilter()
    {
        return false;
    }

    /**
     * Вывод значения в гриде
     *
     * @param $model
     * @return string
     */
    protected function getGridValue($model)
    {
        $value = $model->{$this->attr} ?: '#999';

        return Html::tag('span', $value, [
            'class' => 'label',
            'style' => 'background-color:' . $value,
        ]);
    }
}
