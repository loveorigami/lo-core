<?php

namespace lo\core\db\fields;

use lo\core\inputs\PhoneInput;

/**
 * Class PhoneField
 *
 * @package lo\core\db\fields
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class PhoneField extends TextField
{
    /**
     * @inheritdoc
     */
    public $inputClass = PhoneInput::class;

    /**
     * @var string
     */
    public $pattern = '/^\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/';

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        /** @var array $rules */
        $rules[] = [$this->attr, 'match', 'pattern' => $this->pattern];

        return $rules;
    }
}
