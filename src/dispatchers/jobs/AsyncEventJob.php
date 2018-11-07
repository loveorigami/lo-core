<?php

namespace lo\core\dispatchers\jobs;

/**
 * Class AsyncEventJob
 *
 * @package lo\core\dispatchers\jobs
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class AsyncEventJob extends Job
{
    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }
}
