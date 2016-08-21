# Getting started with Yii2-crud-toolkit

## Need
- lo-module-elfinder
```php
    'modules' => [
        'elfinder' => [
            'class' => 'lo\modules\elfinder\Module',
            'defaultRoute' => 'elfinder/path/index',
            'controllerMap' => [
                'path' => [
                    'class' => lo\modules\elfinder\controllers\PathController::class,
                    'access' => ['admin'],
                    'root' => [
                        'baseUrl' => '', // /uploads
                        'basePath' => '@storagePath', // site.lo/uploads
                        'path'=>'',
                        'access' => ['read' => '*', 'write' => 'root'],
                        'name' => ['category' => 'backend', 'message' => 'Category'],
                        'driver' => 'LocalFileSystem',
                        'options' => [
                            'tmbSize' => '48',
                            'acceptedName' => '/^[0-9a-z_\-.]+$/i', // i любой регистр только англ
                            'imgLib' => 'gd'
                        ]
                    ],
                ],

                'editor' => [
                    'class' => lo\modules\elfinder\controllers\PathController::class,
                    'access' => ['admin'],
                    'root' => [
                        'baseUrl' => '@storageUrl', // /uploads
                        'basePath' => '@storagePath', // site.lo/uploads
                        'path'=>'',
                        'access' => ['read' => '*', 'write' => 'root'],
                        'name' => ['category' => 'backend', 'message' => 'Category'],
                        'driver' => 'LocalFileSystem',
                        'options' => [
                            'tmbSize' => '48',
                            'acceptedName' => '/^[0-9a-z_\-.]+$/i', // i любой регистр только англ
                            'imgLib' => 'gd'
                        ]
                    ],
                ],

                'file-manager' => [
                    'class' => lo\modules\elfinder\controllers\FileManagerController::class,
                ],
            ],
        ],
    ],
```
