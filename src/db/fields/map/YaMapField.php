<?php

namespace lo\core\db\fields\map;

use lo\core\db\fields\TextField;
use lo\core\inputs\map\YaMapInput;

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

    /**
     * @var string
     */
    public $inputClass = YaMapInput::class;

}