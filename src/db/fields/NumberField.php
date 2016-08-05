<?php
namespace lo\core\db\fields;

/**
 * Class NumberField
 * Поле ввода чисел
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class NumberField extends Field
{

    /**
     * @inheritdoc
     */
    public $inputClass = "\\lo\\core\\inputs\\NumberInput";

    /**
     * @var bool число должно быть целым
     */
    public $integerOnly = false;

    /**
     * @var integer|float минимальное значение
     */
    public $min;

    /**
     * @var integer|float максимальное значение
     */
    public $max;

    /**
     * Правила валидации
     * @return array
     */

    public function rules()
    {

        $rules = parent::rules();

	    $numberValidator = [$this->attr, 'number', 'integerOnly'=>$this->integerOnly];

	    if (is_numeric($this->min)) {
		    $numberValidator['min'] = $this->min;
	    }

	    if (is_numeric($this->max)) {
		    $numberValidator['max'] = $this->max;
	    }

	    $rules[] = $numberValidator;

        $rules[] = [$this->attr, 'filter', 'filter' => 'trim'];

        return $rules;

    }

    /**
     * @inheritdoc
     */
    protected function grid()
    {

        $grid = $this->defaultGrid();

        $grid["format"] = "decimal";

        return $grid;

    }

    /**
     * @inheritdoc
     */
    protected function view()
    {

        $view = $this->defaultView();

        $view['format'] = 'decimal';

        return $view;

    }

}