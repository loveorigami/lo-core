<?php
namespace lo\core\db\fields;

/**
 * Class EmailField
 * Поле ввода Email
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class EmailField extends TextField
{
    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->attr, 'email'];
        return $rules;
    }
}