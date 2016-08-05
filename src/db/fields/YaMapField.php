<?php

namespace lo\core\db\fields;

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

    public $inputClass = '\lo\core\inputs\YaMapInput';

}