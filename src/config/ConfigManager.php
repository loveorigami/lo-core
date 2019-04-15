<?php
declare(strict_types=1);

namespace lo\core\config;

use lo\core\config\provider\CollectionProvider;
use lo\core\config\provider\Provider;

final class ConfigManager implements Provider
{
    /**
     * @var CollectionProvider
     */
    private $provider;

    /**
     * @param Provider[] $providers
     */
    public function __construct(array $providers)
    {
        $this->provider = new CollectionProvider($providers);
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->provider->getConfig();
    }
}
