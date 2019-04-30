<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use lo\core\helpers\DateHelper;
use lo\core\inputs\DateInput;

/**
 * Class DateField
 * Поле ввода даты
 *
 * @package lo\core\db\fields
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class DateField extends BaseField
{
    /**
     * @var string формат даты
     */
    public $dateFormat = DateHelper::DB_DATE_FORMAT;

    /**
     * @inheritdoc
     */
    public $inputClass = DateInput::class;

    /**
     * Lower limit of the date.
     *
     * @var string
     */
    public $min;

    /**
     * Upper limit of the date.
     *
     * @var string
     */
    public $max;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->attr, 'date', 'format' => $this->dateFormat, 'except' => ActiveRecord::SCENARIO_SEARCH];
        $rules[] = [$this->attr, 'filter', 'filter' => 'trim'];
        if ($this->min) {
            $rules[] = [$this->attr, 'date', 'format' => $this->dateFormat, 'min' => $this->min];
        }
        if ($this->min) {
            $rules[] = [$this->attr, 'date', 'format' => $this->dateFormat, 'max' => $this->max];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    protected function search(ActiveQuery $query)
    {
        if ($this->model->hasAttribute($this->attr)) {
            $table = $this->model->tableName();
            $attr = $this->attr;
            $query->andFilterWhere(["like", "$table.$attr", $this->model->{$this->attr}]);
        }
    }

}
