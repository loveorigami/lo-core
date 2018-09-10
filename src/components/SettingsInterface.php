<?php

namespace lo\core\components;

/**
 * Interface SettingsInterface
 *
 * @package lo\core\modules\settings\components
 */
interface SettingsInterface
{
    /**
     * @param $key
     * @return string;
     */
    public function get($key): ?string;

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value): void;
}
