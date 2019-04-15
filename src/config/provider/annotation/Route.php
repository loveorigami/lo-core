<?php
declare(strict_types=1);

namespace lo\core\config\provider\provider\annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Route
{
    /**
     * @Required
     *
     * @var string
     */
    public $path;

    /**
     * @var array
     */
    public $methods;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $options;
}
