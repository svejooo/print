<?php

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\User;

/**
 * UserSearch represents the model behind the search form of `shop\entities\User`.
 */
class UserSearch extends Model
{
    public $id;
    public $username;
    public $email;
    public $status;
    public $date_to;
    public $date_from;
    public $updated_at;
    public $created_at;

    public $auth_key;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username',   'email' ], 'safe'],
            [['date_to','date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
               ->andFilterWhere(['like', 'email', $this->email])
               ->andFilterWhere(['>=', 'created_at',  $this->date_from ? strtotime($this->date_from . '00:00:00') : null ])
               ->andFilterWhere(['<=', 'created_at',  $this->date_to ? strtotime($this->date_to . '23:29:59') : null ]);


        //            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
    //            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
