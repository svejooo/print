<?php

namespace backend\forms\Shop;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\shop\Brand;

/**
 * BrandSearch represents the model behind the search form of `shop\entities\shop\Brand`.
 */
class BrandSearch extends Brand
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['id'], 'integer'],
            [['name', 'slug', 'meta_json'], 'safe'],
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
        $query = Brand::find();

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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'meta_json', $this->meta_json]);

        return $dataProvider;
    }
}
