<?php

return [
    'modules' => [
        'permissions' => [
            'class' => 'lo\core\modules\permissions\Module',
			'controllerNamespace' => 'lo\core\modules\permissions\controllers',
			'defaultRoute' => 'constraint'
        ],
    ],
];