<?php
namespace lo\core\db\fields;

use lo\core\inputs\DateInput;

/**
 * Class DateField
 * Поле ввода даты
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DateField extends BaseField
{
    /**
     * @var string формат даты
     */
    public $dateFormat = 'yyyy-MM-dd';

    /**
     * @inheritdoc
     */
    public $inputClass = DateInput::class;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->attr, 'date', 'format' => $this->dateFormat];
        $rules[] = [$this->attr, 'filter', 'filter' => 'trim'];
        return $rules;
    }

}