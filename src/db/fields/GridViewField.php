<?php
namespace lo\core\db\fields;

use lo\core\db\ActiveQuery;
use lo\core\inputs\GridViewInput;
use lo\core\inputs\Select2MultiInput;

/**
 * Class ManyChildField
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class GridViewField extends ManyManyField
{
    /** @var string Класс обработчик по умолчанию */
    public $inputClass = GridViewInput::class;

    /** @var string|array имя класса, либо конфигурация компонента который рендерит поле ввода расширенного фильтра */
    public $filterInputClass = Select2MultiInput::class;
    public $showInFilter = false;
    public $showInExtendedFilter = false;

    /**
     * Редактирование в гриде
     */
    public function xEditable()
    {
        // редактирование через чекбоксы
        return false;
    }

    /**
     * @param ActiveQuery $query
     * @return null
     */
    protected function search(ActiveQuery $query)
    {
       return null;
    }
}