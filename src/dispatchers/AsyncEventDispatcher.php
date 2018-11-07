<?php

namespace lo\core\dispatchers;

use lo\core\dispatchers\jobs\AsyncEventJob;
use yii\queue\Queue;

/**
 * Class AsyncEventDispatcher
 *
 * @package lo\core\dispatchers
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class AsyncEventDispatcher implements EventDispatcher
{
    private $queue;

    /**
     * AsyncEventDispatcher constructor.
     *
     * @param Queue $queue
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param array $events
     */
    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    /**
     * @param $event
     */
    public function dispatch($event): void
    {
        $this->queue->push(new AsyncEventJob($event));
    }
}
