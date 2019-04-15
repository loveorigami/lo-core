<?php
declare(strict_types=1);

namespace lo\core\config\provider\provider\annotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
final class Inject
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param $values
     */
    public function __construct(array $values)
    {
        $this->name = isset($values['name']) ? $values['name'] : '';
        $this->parameters = isset($values['parameters']) ? $values['parameters'] : [];
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }
}
