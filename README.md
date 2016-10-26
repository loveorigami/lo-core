# Yii2-crud-toolkit

Yii2-crud-toolkit is a set for the rapid development of CRUD applications.

> **NOTE:** Toolkit is in initial development. Anything may change at any time.

## Documentation

* [Installation instructions](docs/getting-started.md)
* [Fields and inputs](docs/fields-inputs.md)
* [Features](docs/features.md)

## Migrations
add to console config
```php
        'migrate'=>[
            'class'=>'yii\console\controllers\MigrateController',
            'migrationPath' => null,
            'migrationNamespaces' => [
                ...
                'common\modules\content\migrations',
                'common\modules\base\migrations',
                ...
            ],
            'migrationTable'=>'{{%system_migration}}',
            'templateFile' => '@lo/core/db/views/migration.php',
        ],
```

## License
Yii2-notification-wrapper is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.
