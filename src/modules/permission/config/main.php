<?php

return [
    'modules' => [
        'permission' => [
            'class' => 'lo\core\modules\permission\Module',
			'controllerNamespace' => 'lo\core\modules\permission\controllers',
			'defaultRoute' => 'constraint'
        ],
    ],
];