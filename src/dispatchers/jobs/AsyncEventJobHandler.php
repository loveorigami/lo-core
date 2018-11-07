<?php

namespace lo\core\dispatchers\jobs;

use lo\core\dispatchers\EventDispatcher;

class AsyncEventJobHandler
{
    private $dispatcher;

    /**
     * AsyncEventJobHandler constructor.
     *
     * @param EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param AsyncEventJob $job
     */
    public function handle(AsyncEventJob $job): void
    {
        $this->dispatcher->dispatch($job->event);
    }
}
