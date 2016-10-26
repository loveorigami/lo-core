<?php

return [
    'modules' => [
        'settings' => [
            'class' => 'lo\core\modules\settings\Module',
			'controllerNamespace' => 'lo\core\modules\settings\controllers',
			'defaultRoute' => 'key-storage'
        ],
    ],
];