<?php

namespace lo\core\dispatchers;

class DummyEventDispatcher implements EventDispatcher
{
    public function dispatch($event)
    {
        \Yii::info('Dispatch event ' . get_class($event));
    }

    public function dispatchAll(array $events)
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }
}