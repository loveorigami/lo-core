<?php

use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px'
    ],
    'items' => [
        [
            'label' => \Yii::t('backend', 'Include Item'),
            'url' => ['/main/include-item/index'],
        ],
        [
            'label' => \Yii::t('backend', 'Include Group'),
            'url' => ['/main/include-group/index'],
        ],
    ]
]);