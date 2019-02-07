<?php

return [
    'modules' => [
        'core' => [
            'aliases' => [
                '@sxdPath' => $params['sxd.path'],
                '@sxdUrl' => $params['sxd.url'],
            ],
            'class' => 'lo\core\modules\core\Module',
            'controllerNamespace' => 'lo\core\modules\core\controllers',
            'defaultRoute' => 'sypex-dumper',
        ],
        'elfinder' => [
            'controllerMap' => [
                'sxd' => [
                    'class' => lo\modules\elfinder\controllers\PathController::class,
                    'access' => ['root'],
                    'root' => [
                        'baseUrl' => '', // /uploads
                        'basePath' => $params['sxd.path'], // site.lo/uploads
                        'path' => '',
                        'access' => ['read' => '*', 'write' => 'root'],
                        'name' => ['category' => 'backend', 'message' => 'Category'],
                        'driver' => 'LocalFileSystem',
                        'options' => [
                            'tmbSize' => '48',
                            'acceptedName' => '/^[0-9a-z_\-.]+$/i', // i любой регистр только англ
                            'imgLib' => 'gd',
                        ],
                    ],
                ],
            ],
        ],
        'params' => $params,
    ],
];
