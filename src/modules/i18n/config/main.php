<?php

return [
    'components' => [
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                /* Uncomment this code to use DbMessageSource**/
                '*' => [
                    'class' => yii\i18n\DbMessageSource::class,
                    'sourceMessageTable' => '{{%i18n__source_message}}',
                    'messageTable' => '{{%i18n__message}}',
                    'enableCaching' => true,
                    'cache' => 'cacheCommon',
                    'cachingDuration' => 3600,
                    'on missingTranslation' => [
                        //lo\core\modules\i18n\Module::class, 'missingTranslation'
                    ]
                ],
            ],
        ],
    ],
    'modules' => [
        'i18n' => [
            'class' => 'lo\core\modules\i18n\Module',
            'controllerNamespace' => 'lo\core\modules\i18n\controllers',
            'defaultRoute' => 'i18n-message',
        ],
    ],
];