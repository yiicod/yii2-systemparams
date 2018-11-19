<?php

namespace yiicod\systemparams\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "SystemConfig".
 *
 * The followings are the available columns in table 'SystemConfig':
 *
 * @property int $id
 * @property string $description
 * @property string $param_key
 * @property string $validator
 * @property string $param_value
 */
class SystemParamModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_param';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['param_key'], 'required'],
            [['param_key', 'param_value'], 'string', 'max' => 100],
            ['param_value', 'email', 'skipOnEmpty' => true, 'when' => function ($model) {
                return 'email' == $model->validator;
            }],
            ['param_value', 'integer', 'when' => function ($model) {
                return 'integer' == $model->validator;
            }],
            ['param_value', 'url', 'skipOnEmpty' => true, 'when' => function ($model) {
                return 'url' == $model->validator;
            }],
            ['param_value', 'string', 'skipOnEmpty' => true, 'when' => function ($model) {
                return 'string' == $model->validator;
            }],
            ['param_value', 'boolean', 'when' => function ($model) {
                return 'boolean' == $model->validator;
            }],
            [['description'], 'string', 'max' => 255],
            [['validator'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'param_key' => 'Param Key',
            'param_value' => 'Param Value',
            'description' => 'Description',
            'validator' => 'Validator',
        ];
    }

    /**
     * Get attributes map
     *
     * @return array
     */
    public static function attributesMap()
    {
        return [
            'fieldParamKey' => 'param_key',
            'fieldParamValue' => 'param_value',
            'fieldValidator' => 'validator',
            'fieldDescription' => 'description',
        ];
    }
}
