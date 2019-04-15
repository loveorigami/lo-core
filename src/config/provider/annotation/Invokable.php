<?php
declare(strict_types=1);

namespace lo\core\config\provider\provider\annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Invokable
{
    /**
     * @var string
     */
    public $id;
}
