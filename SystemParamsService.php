<?php

namespace yiicod\systemparams;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class SystemParamService
 * System params service to work with
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package yiicod\systemparam
 */
class SystemParamsService
{
    const ARRAY_KEYS_SEPARATOR = '.';
    const PARAM_IS_NOT_SET = 'CONFIG_VALUE_IS_NOT_SET';
    const CACHE_KEY = 'YII_SYSTEM_PARAM';

    /**
     * Cache duration
     *
     * @var int
     */
    public $cacheDuration = 0;

    /**
     * Params array
     *
     * @var array
     */
    private $params = [];

    /**
     * Get system param
     *
     * @param string $param
     *
     * @return mixed
     */
    public function getParam(string $param)
    {
        if (empty($this->params)) {
            $this->prepareParams();
        }

        return ArrayHelper::getValue($this->params, $param);
    }

    /**
     * Prepare params
     *
     * @return bool
     */
    protected function prepareParams()
    {
        $paramModel = Yii::$app->get('systemparams')->modelMap['systemParam']['class'];

        //If table not exists
        if (Yii::$app instanceof \yii\console\Application && null === Yii::$app->db->schema->getTableSchema($paramModel::tableName())) {
            Yii::error('Table ' . $paramModel::tableName() . ' not exists');

            return false;
        }

        if (0 === $this->cacheDuration || false === $this->loadParamsFromCache()) {
            $this->loadParamsFromDb();
            //Set params to cache
            if (isset(Yii::$app->cache)) {
                Yii::$app->cache->set(self::CACHE_KEY, Json::encode($this->params), $this->cacheDuration);
            }
        }

        return true;
    }

    /**
     * Load params from db.
     *
     * @return bool
     */
    protected function loadParamsFromCache()
    {
        if ((isset(Yii::$app->cache) && $this->cacheDuration > 0 && false !== Yii::$app->cache->get(self::CACHE_KEY))) {
            $this->params = Json::decode(Yii::$app->cache->get(self::CACHE_KEY));

            return true;
        }

        return false;
    }

    /**
     * Load params from db.
     *
     * @return bolean
     */
    protected function loadParamsFromDb()
    {
        $paramModel = Yii::$app->get('systemparams')->modelMap['systemParam']['class'];

        $records = $paramModel::find()->asArray()->all();

        foreach ($records as $record) {
            $this->params = ArrayHelper::merge($this->params, $this->generateArrayForParamValue(
                $record[$paramModel::attributesMap()['fieldParamKey']],
                $record[$paramModel::attributesMap()['fieldParamValue']]
            ));
        }

        return true;
    }

    /**
     * Merge params
     *
     * @param $params
     * @param bool $autoUpdate
     *
     * @return bool
     */
    public function mergeParams(array $params, bool $autoUpdate = false)
    {
        $paramModel = Yii::$app->get('systemparams')->modelMap['systemParam']['class'];

        $arrayOfKeys = $this->getArrayKeysRecursivelyInString($params);

        $records = $paramModel::find()->all();

        foreach ($records as $i => $model) {
            $param = $model->{$paramModel::attributesMap()['fieldParamKey']};
            if (self::PARAM_IS_NOT_SET !== $this->getParamValueByKey($param, $params)) {
                $records[$param] = clone $model;
            } else {
                $model->delete();
            }
            unset($records[$i]);
        }

        foreach ($arrayOfKeys as $paramKey) {
            /** @var ActiveRecord $model */
            $model = new $paramModel();
            if (isset($records[$paramKey])) {
                $model->setIsNewRecord(false);
                $model->setAttributes($records[$paramKey]->getAttributes(), false);
            }

            $model->{$paramModel::attributesMap()['fieldParamKey']} = $paramKey;

            $value = $this->getParamValueByKey($paramKey, $params);

            if (false === empty($value)) {
                $model->{$paramModel::attributesMap()['fieldParamValue']} = is_bool($value['value']) ? intval($value['value']) : $value['value'];
                $model->{$paramModel::attributesMap()['fieldValidator']} = isset($value['validator']) ? $value['validator'] : 'string';
                $model->{$paramModel::attributesMap()['fieldDescription']} = isset($value['description']) ? $value['description'] : '';
            } else {
                $model->{$paramModel::attributesMap()['fieldParamValue']} = is_bool($value) ? intval($value) : $value;
                $model->{$paramModel::attributesMap()['fieldValidator']} = 'string';
                $model->{$paramModel::attributesMap()['fieldDescription']} = '';
            }

            if ($model->isNewRecord || (count(array_diff(
                        [
                            $model->{$paramModel::attributesMap()['fieldParamValue']},
                            $model->{$paramModel::attributesMap()['fieldValidator']},
                            $model->{$paramModel::attributesMap()['fieldDescription']},
                        ],
                        [
                            $records[$paramKey]->{$paramModel::attributesMap()['fieldParamValue']},
                            $records[$paramKey]->{$paramModel::attributesMap()['fieldValidator']},
                            $records[$paramKey]->{$paramModel::attributesMap()['fieldDescription']},
                        ]
                    )) && $autoUpdate)
            ) {
                if (false == $model->save()) {
                    Yii::error($paramKey . ' ' . Json::encode($model->getErrors()), 'system.systemparam');
                }
            }
        }

        self::flushCache();

        return true;
    }

    /**
     * Flush system params cache
     */
    public static function flushCache()
    {
        if (isset(Yii::$app->cache)) {
            Yii::$app->cache->delete(self::CACHE_KEY);
        }
    }

    /**
     * Generate array of keys recursively in string.
     *
     * @param $dataArray
     *
     * @return array
     *
     * @author Virchenko Maksim <muslim1992@gmail.com>
     */
    protected function getArrayKeysRecursivelyInString(array $dataArray): array
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($dataArray));
        $keys = [];

        foreach ($iterator as $key => $value) {
            // Build long key name based on parent keys
            $key = $iterator->getSubIterator(0)->key();
            for ($i = 1; $i < $iterator->getDepth(); ++$i) {
                $key = $key . self::ARRAY_KEYS_SEPARATOR . $iterator->getSubIterator($i)->key();
            }
            $keys[] = $key;
        }

        return array_unique($keys);
    }

    /**
     * Return param value or false if param not exist.
     *
     * @param $pk
     * @param $array
     *
     * @return bool
     *
     * @author Chaykovskiy Roman
     */
    protected function getParamValueByKey($pk, $array)
    {
        $keys = explode(self::ARRAY_KEYS_SEPARATOR, $pk);

        foreach ($keys as $key) {
            $array = isset($array[$key]) ? $array[$key] : self::PARAM_IS_NOT_SET;
            if ($array == self::PARAM_IS_NOT_SET) {
                break;
            }
        }

        return $array;
    }

    /**
     * Generate multidimensional array for pk with value.
     *
     * @param $pk can be string like 'a|b...|n'
     * @param $value
     *
     * @return array
     *
     * @author Chaykovskiy Roman
     */
    protected function generateArrayForParamValue($pk, $value)
    {
        $keys = explode(self::ARRAY_KEYS_SEPARATOR, $pk);
        $countOfKeys = count($keys);
        $result = [];

        $current = &$result;
        foreach ($keys as $k => $keyValue) {
            if ($countOfKeys == ($k + 1)) {
                $current[$keyValue] = $value;
            } else {
                $current[$keyValue] = [];
                // descend into the new array
                $current = &$current[$keyValue];
            }
        }

        return $result;
    }
}
