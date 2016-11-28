<?php

return [
    'modules' => [
        'i18n' => [
            'class' => 'lo\core\modules\i18n\Module',
			'controllerNamespace' => 'lo\core\modules\i18n\controllers',
            'defaultRoute' => 'i18n-message',
            'menuItems' => [
                [
                    'label' => \Yii::t('backend', 'I18n Messages'),
                    'url' => ['/i18n/i18n-message/index'],
                ],
                [
                    'label' => \Yii::t('backend', 'I18n Source Messages'),
                    'url' => ['/i18n/i18n-source-message/index'],
                ],
            ]
        ],
    ],
];