# Install

- Определить алиасы ```@storage``` и ```@storageUrl```
- Запустить миграции
```bash
$ php yii migrate/up --migrationPath=@vendor/loveorigami/lo-core/migrations
```
- Подключить в конфигурации необходимые модули
```php
return [
    'modules' => [
        'permission' => [
            'class' => lo\core\modules\permission\modules\admin\Module::class,
            'defaultRoute' => 'constraint'
        ],
    ],
];
```
