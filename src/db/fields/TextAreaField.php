<?php
namespace lo\core\db\fields;

use lo\core\inputs\TextAreaInput;

/**
 * Class TextAreaField
 * Поле текстовой области модели
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TextAreaField extends TextField
{
    /** @var bool отображать в гриде */
    public $showInGrid = false;

    /** @var bool отображать в фильтре грида */
    public $showInFilter = false;

    /** @var bool отображать в расширенном фильре */
    public $showInExtendedFilter = false;

    /** @var string класс по умолчанию */
    public $inputClass = TextAreaInput::class;

    /**
     * @return array
     */
    protected function view()
    {
        $view = parent::view();
        $view['format'] = 'html';
        return $view;
    }
}