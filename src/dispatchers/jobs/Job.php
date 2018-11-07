<?php

namespace lo\core\dispatchers\jobs;

use Yii;
use yii\base\InvalidConfigException;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class Job
 *
 * @package lo\core\dispatchers\jobs
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class Job implements JobInterface
{
    /**
     * @param Queue $queue
     * @throws InvalidConfigException
     */
    public function execute($queue): void
    {
        $listener = $this->resolveHandler();
        $listener($this, $queue);
    }

    /**
     * @return callable
     * @throws InvalidConfigException
     */
    private function resolveHandler(): callable
    {
        return [Yii::createObject(static::class . 'Handler'), 'handle'];
    }
}
