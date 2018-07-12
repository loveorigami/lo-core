<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;

/**
 * Class EmailField
 * Поле ввода Email
 *
 * @package lo\core\db\fields
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class EmailField extends TextField
{
    /** @var bool $checkDns */
    public $checkDNS = false;

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->attr, 'email', 'checkDNS' => $this->checkDNS, 'except' => ActiveRecord::SCENARIO_SEARCH];

        return $rules;
    }
}
