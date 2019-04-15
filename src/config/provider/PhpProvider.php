<?php
declare(strict_types=1);

namespace lo\core\config\provider;

use FilesystemIterator;
use GlobIterator;
use lo\core\config\util\ArrayUtil;

final class PhpProvider implements Provider
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @inheritdoc
     */
    public function getConfig(): array
    {
        $config = [];
        foreach (new GlobIterator($this->pattern, FilesystemIterator::SKIP_DOTS) as $file) {
            $config = ArrayUtil::merge($config, include $file->getRealPath());
        }

        return $config;
    }
}
