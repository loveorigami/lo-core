<?php

namespace lo\core\exceptions;

use Yii;
use yii\base\Exception;

/**
 * Class FlashException
 * @package lo\core\exceptions
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class FlashException extends Exception
{
    private $type;

    /**
     * FlashException constructor.
     * @param string $type
     * @param int $message
     * @param Exception|null $previous
     */
    public function __construct($type, $message, Exception $previous = null)
    {
        $this->type = $type;
        $this->message = $message;
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * add to session
     */
    public function catchFlash()
    {
        Yii::$app->session->setFlash($this->getType(), $this->getMessage());
    }
}
