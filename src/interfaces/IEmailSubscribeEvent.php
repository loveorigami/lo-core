<?php

namespace lo\core\interfaces;

/**
 * Interface IEmailSubscribeEvent
 * @package lo\core\interfaces
 */
interface IEmailSubscribeEvent
{
    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getName();

}