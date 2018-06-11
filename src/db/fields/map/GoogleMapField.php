<?php

namespace lo\core\db\fields\map;

use lo\core\db\fields\TextField;
use lo\core\inputs\map\GoogleMapInput;

/**
 * Class GoogleMapField
 * @package lo\core\db\fields\map
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class GoogleMapField extends TextField
{
    /**
     * @inheritdoc
     */
    public $showInExtendedFilter = false;

    /**
     * @var string
     */
    public $inputClass = GoogleMapInput::class;

}