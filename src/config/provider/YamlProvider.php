<?php
declare(strict_types=1);

namespace lo\core\config\provider;

use FilesystemIterator;
use GlobIterator;
use Symfony\Component\Yaml\Parser;
use lo\core\config\util\ArrayUtil;

final class YamlProvider implements Provider
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
        $this->parser = new Parser();
    }

    /**
     * @inheritdoc
     */
    public function getConfig(): array
    {
        $config = [];
        foreach (new GlobIterator($this->pattern, FilesystemIterator::SKIP_DOTS) as $file) {
            $config = ArrayUtil::merge($config, $this->parser->parse(file_get_contents($file->getRealPath())));
        }

        return $config;
    }
}
