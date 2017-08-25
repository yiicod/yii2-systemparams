<?php

namespace yiicod\systemparams;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Cms extension settings.
 *
 * @author Orlov Alexey <aaorlov88@gmail.com>
 */
class SystemParams extends Component implements BootstrapInterface
{
    /**
     * @var bool
     */
    public $commandMap = [];

    /**
     * @var array table settings
     */
    public $modelMap = [];

    /**
     * Cache durations.
     *
     * @var int
     */
    public $cacheDuration;

    /**
     * System params service instance
     *
     * @var null
     */
    private static $service;

    /**
     * Init components, Merge config.
     */
    public function bootstrap($app)
    {
        //Merge main extension config with local extension config
        $config = include dirname(__FILE__) . '/config/main.php';

        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $this->{$key} = ArrayHelper::merge($value, $this->{$key});
            } elseif (null === $this->{$key}) {
                $this->{$key} = $value;
            }
        }

        self::$service = Yii::createObject([
            'class' => SystemParamsService::class,
            'cacheDuration' => $this->cacheDuration,
        ]);

        //Merge commands map
        if (Yii::$app instanceof \yii\console\Application) {
            Yii::$app->controllerMap = ArrayHelper::merge($this->commandMap, Yii::$app->controllerMap);
            Yii::$app->controllerMap = array_filter(Yii::$app->controllerMap);
        }

        Yii::setAlias('@yiicod', realpath(dirname(__FILE__) . '/..'));
        Yii::setAlias('@yiicod_systemparams_migrations', realpath(dirname(__FILE__) . '/migrations'));
    }

    /**
     * Get parameter
     *
     * @param string $param
     * @param mixed $default
     *
     * @return mixed
     */
    public static function getParam(string $param, $default = null)
    {
        $value = self::$service->getParam($param);

        return $value ? $value : $default;
    }
}
