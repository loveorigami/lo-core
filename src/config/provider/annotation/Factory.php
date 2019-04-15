<?php
declare(strict_types=1);

namespace lo\core\config\provider\provider\annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Factory
{
    /**
     * @Required
     *
     * @var string
     */
    public $id;
}
