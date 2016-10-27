<?php

namespace lo\core\modules\settings\components;

/**
 * Interface SettingsInterface
 * @package lo\core\modules\settings\components
 */
interface SettingsInterface
{
    /**
     * @param $key
     * @return string;
     */
    public function get($key);

    /**
     * @param $key
     * @param $value
     * @return string
     */
    public function set($key, $value);
}