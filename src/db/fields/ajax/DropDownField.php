<?php
namespace lo\core\db\fields\ajax;

use lo\core\inputs\ajax\Select2AjaxInput;

/**
 * Class AjaxOneField
 * Поле для связей Has One. Интерфейс привязки в форме в виде выпадающего списка с ajax выбором.
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DropDownField extends AjaxField
{
    public $inputClass = Select2AjaxInput::class;
}