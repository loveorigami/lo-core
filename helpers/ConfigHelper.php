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
     * @param array $dirs массив дирректорий с идентификаторами модулей которые необходимо подключить
     * @return array
     */

    public static function getModulesConfigs($dirs)
    {

        $config = [];

        foreach ($dirs AS $dir) {
            foreach ($dir['modules'] AS $code) {
                $path = str_replace("{module}", $code, $dir['path']);
                $file = \Yii::getAlias($path);
                if (is_file($file)) {
                    $config = \yii\helpers\ArrayHelper::merge($config, require($file));
                }
            }
        }

        return $config;

    }

}