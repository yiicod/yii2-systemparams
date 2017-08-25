<?php

namespace yiicod\systemparams\actions\backend;

use Yii;
use yii2mod\editable\EditableAction;
use yiicod\systemparams\SystemParamsService;

/**
 * Class UpdateAction
 *
 * @package yiicod\systemparams\actions\backend\systemParams
 */
class Update extends EditableAction
{
    /**
     * Init action
     */
    public function init()
    {
        $this->modelClass = Yii::$app->get('systemparams')->modelMap['systemParam']['class'];
        parent::init();
    }

    /**
     * After action runs
     */
    public function afterRun()
    {
        SystemParamsService::flushCache();
    }
}
