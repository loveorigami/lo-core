<?php

namespace lo\core\dispatchers;

class DeferredEventDispatcher implements EventDispatcher
{
    private $defer = false;
    private $queue = [];
    private $next;

    public function __construct(EventDispatcher $next)
    {
        $this->next = $next;
    }

    public function dispatchAll(array $events)
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch($event)
    {
        if ($this->defer) {
            $this->queue[] = $event;
        } else {
            $this->next->dispatch($event);
        }
    }

    public function defer()
    {
        $this->defer = true;
    }

    public function clean()
    {
        $this->queue = [];
        $this->defer = false;
    }

    public function release()
    {
        foreach ($this->queue as $i => $event) {
            $this->next->dispatch($event);
            unset($this->queue[$i]);
        }
        $this->defer = false;
    }
}