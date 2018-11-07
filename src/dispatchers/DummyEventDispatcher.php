<?php

namespace lo\core\dispatchers;

use Yii;

/**
 * Class DummyEventDispatcher
 *
 * @package lo\core\dispatchers
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class DummyEventDispatcher implements EventDispatcher
{
    /**
     * @param $event
     */
    public function dispatch($event): void
    {
        Yii::info('Dispatch event ' . \get_class($event));
    }

    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }
}
