<?php

use yiicod\systemparams\commands\SystemParamCommand;
use yiicod\systemparams\models\SystemParamModel;
use yiicod\systemparams\models\SystemParamSearch;

return [
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
];
