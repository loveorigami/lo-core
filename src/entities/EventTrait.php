<?php
namespace lo\core\entities;

/**
 * Trait EventTrait
 * @package lo\core\entities
 */
trait EventTrait
{
    private $events = [];

    protected function recordEvent($event)
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}