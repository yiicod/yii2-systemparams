SystemParam extension (Yii 2)
=============================

With this extension you easy config your Yii::$app->params from admin panel. 
You need install extension with composer and run command:
```php
php yii params sync
```
This is command for synchronize your php config with db.

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run
```
php composer.phar require --prefer-dist yiicod/systemparam "*"
```
or add
```json
"yiicod/systemparam": "*"
```
to your composer.json

Config ( This is all config for extensions ):
---------------------------------------------

```php
'components' => array(
    ...
    'systemparams => [
        'commandMap' => [
            'params' => [
                'class' => SystemParamCommand::class,
                'paramsAlias' => '@app/../common/config/params-system.php',
            ],
        ],
        'modelMap' => [
            'systemParam' => [
                'class' => SystemParamModel::class,
            ],
            'systemParamSearch' => [
                'class' => SystemParamSearch::class,
            ],
        ],
        'cacheDuration' => 28800,
        'components' => [],
    ]
    ...
)
'bootstrap' => array('systemparams')
```

Usage
-----

Use (or extend) yiicod\systemparams\controllers, or add to your controller crud actions:
```php
public function actions()
{
    return [
        'admin' => [
            'class' => yiicod\systemparam\actions\admin\Admin::class,
        ],
        'update' => [
            'class' => yiicod\systemparam\actions\admin\Update::class,
        ],
    ];
}
```

Add migrations namespace:
```php
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'console\migrations',
                'yiicod_systemparam_migrations',
            ],
            'migrationPath' => null, // allows to disable not namespaced migration completely
        ],
    ],
```