<?php
namespace lo\core\db\fields;

use lo\core\inputs\PasswordInput;

/**
 * Class PasswordField
 * Поле ввода пароля модели
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class PasswordField extends TextField
{
    /**
     * @inheritdoc
     */
    public $inputClass = PasswordInput::class;

    /**
     * Длина пароля
     */
    public $passwordLength = 6;

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->attr, 'string', 'min' => $this->passwordLength];
        return $rules;
    }
}