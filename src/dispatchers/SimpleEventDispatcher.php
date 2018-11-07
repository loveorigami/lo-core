<?php

namespace lo\core\dispatchers;

use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\di\NotInstantiableException;

class SimpleEventDispatcher implements EventDispatcher
{
    private $container;
    private $listeners;

    public function __construct(Container $container, array $listeners)
    {
        $this->container = $container;
        $this->listeners = $listeners;
    }

    /**
     * @param array $events
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function dispatchAll(array $events):void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    /**
     * @param $event
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function dispatch($event):void
    {
        $eventName = \get_class($event);
        if (array_key_exists($eventName, $this->listeners)) {
            foreach ($this->listeners[$eventName] as $listenerClass) {
                $listener = $this->resolveListener($listenerClass);
                $listener($event);
            }
        }
    }

    /**
     * @param $listenerClass
     * @return callable
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function resolveListener($listenerClass): callable
    {
        return [$this->container->get($listenerClass), 'handle'];
    }
}
