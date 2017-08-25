<?php

namespace yiicod\systemparams\controllers\backend;

use yii\web\Controller;
use yiicod\systemparams\actions\backend\Admin;
use yiicod\systemparams\actions\backend\Update;

/**
 * Class ParamController
 * System param controller
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package yiicod\systemparams\controllers\backend
 */
class SystemParamController extends Controller
{
    /**
     * Default action
     *
     * @var string
     */
    public $defaultAction = 'admin';

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return [
            'admin' => [
                'class' => Admin::class,
            ],
            'update' => [
                'class' => Update::class,
            ],
        ];
    }
}
