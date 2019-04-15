<?php
declare(strict_types=1);

namespace lo\core\config\provider;

abstract class ModuleConfigProvider implements Provider
{
    /**
     * @inheritdoc
     */
    abstract public function getConfig(): array;
}
