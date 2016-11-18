<?php

namespace lo\core\inputs\ajax;

use lo\core\db\fields\ajax\AjaxField;
use lo\core\inputs\DropDownInput;

/**
 * Class AjaxInput
 * @package lo\core\inputs\ajax
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class AjaxInput extends DropDownInput
{
    /**
     * @return string
     */
    public function getLoadUrl()
    {
        /** @var AjaxField $modelField */
        $modelField = $this->modelField;
        return $modelField->getLoadUrl();
    }
}