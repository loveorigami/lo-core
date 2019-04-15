<?php
declare(strict_types=1);

namespace lo\core\config\provider\provider\annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Middleware
{
    /**
     * @Required
     *
     * @var string
     */
    public $path;

    /**
     * @var int
     */
    public $priority;

    /**
     * @var string
     */
    public $name;
}
