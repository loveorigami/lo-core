<?php

return [
    'modules' => [
        'permissions' => [
            'class' => 'lo\core\modules\permission\Module',
			'controllerNamespace' => 'lo\core\modules\permission\controllers',
			'defaultRoute' => 'constraint'
        ],
    ],
];