<?php
namespace lo\core\interfaces;

/**
 * Interface ITimelineEvent
 * @package lo\core\interfaces
 */
interface ITimelineEvent
{
    /**
     * @return string
     */
    public function getCategory();

    /**
     * @return array
     */
    public function getData();

    /**
     * @return string
     */
    public function getEvent();

}