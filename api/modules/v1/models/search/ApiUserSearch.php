<?php

namespace api\modules\v1\models\search;

use api\modules\v1\models\ApiUser;
use common\components\validators\ForbiddenFieldValidator;
use common\enums\Language;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ApiUser search model for API
 */
class ApiUserSearch extends ApiUser
{
    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'status', 'external_id'], 'integer'],
            [['login', 'first_name', 'last_name', 'middle_name', 'country', 'language'], 'safe'],
            [['email', 'phone', 'status', 'external_id', 'role'], ForbiddenFieldValidator::class],
            [['language'], 'in', 'range' => Language::getKeys()],
        ];
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
        $query = ApiUser::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'group_id' => $this->group_id,
            'status' => $this->status,
            'language' => $this->language,
        ]);

        $query->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'role', $this->role]);

        return $dataProvider;
    }
}
