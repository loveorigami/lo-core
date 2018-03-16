<?php

namespace lo\core\widgets\awcheckbox;

/**
 * Class LabelDto
 * @package lo\core\widgets\awcheckbox
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class LabelDto
{
    public $name;
    public $group;
    public $inputOptions = [];

    public function __construct($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        } else {
            $this->name = $data;
        }
    }
}