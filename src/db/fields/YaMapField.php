<?php

namespace lo\core\db\fields;

use lo\core\inputs\YaMapInput;

/**
 * Class YaMapField
 * Поле выбора координат на яндекс карте
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */

class YaMapField extends TextField
{
    /**
     * @inheritdoc
     */
    public $showInExtendedFilter = false;

    public $inputClass = YaMapInput::class;

}