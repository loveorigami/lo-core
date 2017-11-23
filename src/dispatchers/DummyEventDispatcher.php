<?php

namespace lo\core\dispatchers;

class DummyEventDispatcher implements EventDispatcher
{
    public function dispatch($event): void
    {
        \Yii::info('Dispatch event ' . get_class($event));
    }

    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }
}