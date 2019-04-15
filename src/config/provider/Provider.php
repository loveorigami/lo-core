<?php
declare(strict_types=1);

namespace lo\core\config\provider;

interface Provider
{
    /**
     * @return array
     */
    public function getConfig(): array;
}
