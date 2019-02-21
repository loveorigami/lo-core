<?php

use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px'
    ],
    'items' => [
        [
            'label' => \Yii::t('backend', 'I18n Messages'),
            'url' => ['/i18n/i18n-message/index'],
        ],
        [
            'label' => \Yii::t('backend', 'I18n Source Messages'),
            'url' => ['/i18n/i18n-source-message/index'],
        ],
    ]
]);

