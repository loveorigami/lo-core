<?php

namespace lo\core\db\fields;

use lo\core\grid\XEditableColumn;
use lo\core\helpers\ArrayHelper;
use lo\core\inputs\DropDownInput;
use lo\core\db\ActiveRecord;

/**
 * Class ListField
 * Списочное поле модели. Поддерживает возможность создания зависимых списков.
 *
 * @package lo\core\db\fields
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class ListField extends BaseField
{
    /** @var bool значения выпадающего списка - числовые */
    public $numeric = false;

    /** @inheritdoc */
    public $inputClass = DropDownInput::class;

    public $editAsGroup = true;

    /**
     * @return array
     */
    public function xEditable()
    {
        $dataArr = (array)$this->defaultGridFilter();
        $data = [];

        $first = ArrayHelper::first($dataArr);

        if (\is_array($first)) {
            foreach ($dataArr as $key => $value) {
                $data[] = [
                    'text' => $key,
                    'children' => $value,
                ];
            }
        } else {
            $data = $dataArr;
        }

        return [
            'class' => XEditableColumn::class,
            'url' => $this->getEditableUrl(),
            'dataType' => 'select',
            'format' => 'raw',
            'editable' => ['source' => $data],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultGridFilter()
    {
        return $this->getDataValue();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();

        if ($this->numeric) {
            $rules[] = [$this->attr, 'integer', 'except' => [ActiveRecord::SCENARIO_SEARCH]];
        }

        if ($this->numeric AND $this->defaultValue === null) {
            $rules[] = [$this->attr, 'default', 'value' => 0, 'except' => [ActiveRecord::SCENARIO_SEARCH]];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();

        $grid["value"] = function ($model, $index, $widget) {

            $value = $model->{$this->attr};

            if (is_string($value) OR is_int($value)) {
                return ArrayHelper::getValue($this->getDataValue(), $value, $value);
            } else {
                return $value;
            }
        };

        return $grid;

    }

    /**
     * @inheritdoc
     */
    protected function view()
    {
        $view = $this->defaultView();

        $value = $this->model->{$this->attr};

        if (is_string($value) OR is_int($value)) {
            $view["value"] = ArrayHelper::getValue($this->getDataValue(), $value, $value);
        }

        return $view;
    }
}
