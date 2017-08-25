<?php

namespace yiicod\systemparams\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SystemParamSearch represents the model behind the search form about `yiicod\systemparams\models\SystemParamModel`.
 */
class SystemParamSearch extends SystemParamModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['param_key', 'param_value', 'description', 'validator'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SystemParamModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'param_key', $this->param_key])
            ->andFilterWhere(['like', 'param_value', $this->param_value])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'validator', $this->validator]);

        return $dataProvider;
    }
}
