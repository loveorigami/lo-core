<?php

namespace lo\core\dispatchers;

use shop\jobs\AsyncEventJob;
use zhuravljov\yii\queue\Queue;

class AsyncEventDispatcher implements EventDispatcher
{
    private $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function dispatchAll(array $events)
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch($event)
    {
        $this->queue->push(new AsyncEventJob($event));
    }
}