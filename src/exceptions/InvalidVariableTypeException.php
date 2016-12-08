<?php

namespace lo\core\exceptions;

use yii\base\Exception;

/**
 * Class InvalidVariableTypeException
 * @package lo\core\exceptions
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class InvalidVariableTypeException  extends Exception
{
    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->message = "The variable must be of the type \"{$type}\".";
        $this->code    = 0;
    }
}
