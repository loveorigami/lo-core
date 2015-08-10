<?php

namespace lo\core\helpers;

/**
 * Class ConfigHelper
 * Хелпер для формирования конфигов
 * @package lo\core\helpers
 * @author Churkin Anton <webadmin87@gmail.com>
 */

class ConfigHelper
{

    /**
     * Возвращает конфигурацию модулей
     * @param array $modules массив идентификаторов модулей которые необходимо подключить
     * @param string $path путь до папки содеожащей модули
     * @param string $configPath путь к конфигу относительно папки модуля
     * @return array
     */

    public static function getModulesConfigs($modules, $path = "@lo", $configPath = "config/main.php")
    {

        $config = [];

        foreach ($modules AS $code) {

            $file = \Yii::getAlias("$path/modules/$code/modules/admin/$configPath");

            if (is_file($file)) {

                $config = \yii\helpers\ArrayHelper::merge($config, require($file));

            }

        }
        return $config;

    }

}