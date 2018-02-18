Yii SystemParam extension
=========================

[![Latest Stable Version](https://poser.pugx.org/yiicod/yii2-systemparams/v/stable)](https://packagist.org/packages/yiicod/yii2-systemparams) [![Total Downloads](https://poser.pugx.org/yiicod/yii2-systemparams/downloads)](https://packagist.org/packages/yiicod/yii2-systemparams) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiicod/yii2-systemparams/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiicod/yii2-systemparams/?branch=master)[![Code Climate](https://codeclimate.com/github/yiicod/yii2-systemparams/badges/gpa.svg)](https://codeclimate.com/github/yiicod/yii2-systemparams)

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

#### Config:

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

#### Usage

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

#### Migration usage

Migration command or use manual(http://www.yiiframework.com/doc-2.0/guide-db-migrations.html) for configuration:
```php
   yii migrate --migrationPath=@vendor/yiicod/yii2-systemparam/migrations
```