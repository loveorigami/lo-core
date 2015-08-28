<?php

namespace lo\core\db\fields;

/**
 * Class YaMapField
 * Поле выбора координат на яндекс карте
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */



class YaMapField extends TextField
{
    /**
     * @inheritdoc
     */
    public $showInExtendedFilter = false;

    public $inputClass = '\lo\core\inputs\YaMapInput';

}