<?php

namespace lo\core\inputs;

/**
 * Class Select2Input
 * Выпадающий список, стилизированный виджетом Select2 c множественным выбором
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Select2MultiInput extends Select2Input
{
    public $options = [
        "multiple" => true,
        'encode' => false
    ];
}