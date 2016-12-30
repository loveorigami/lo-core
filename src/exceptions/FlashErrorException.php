<?php

namespace lo\core\exceptions;
use Exception;

/**
 * Class FlashErrorException
 * @package lo\core\exceptions
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class FlashErrorException extends FlashException
{
    /**
     * FlashException constructor.
     * @param string $message
     * @param Exception|null $previous
     */
    public function __construct($message, Exception $previous = null)
    {
        $this->message = $message;
        parent::__construct('error', $message, $previous);
    }
}
