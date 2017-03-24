<?php
namespace lo\core\db\fields;

use lo\core\inputs\DropDownInput;
use lo\core\db\ActiveRecord;

/**
 * Class ListField
 * Списочное поле модели. Поддерживает возможность создания зависимых списков.
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class EachField extends BaseField
{
    /** @var bool значения выпадающего списка - числовые */
    public $numeric = false;

    /** @inheritdoc */
    public $inputClass = DropDownInput::class;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();

        if ($this->numeric) {
            $rules[] = [$this->attr, 'each', 'rule' => ['integer'], 'except' => [ActiveRecord::SCENARIO_SEARCH]];
        }

        return $rules;
    }
}