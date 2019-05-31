<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveQuery;
use lo\core\inputs\ColorInput;

/**
 * Class ColorField
 *
 * @package lo\core\db\fields
 */
class ColorField extends TextField
{
    public $inputClass = ColorInput::class;
}
