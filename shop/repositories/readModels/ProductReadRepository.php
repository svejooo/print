<?php


namespace shop\repositories\readModels;


use shop\entities\shop\Category;
use shop\entities\shop\Product\Product;
use shop\entities\shop\Product\Value;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class ProductReadRepository
{

    public function search($form)
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category');

        // Если приходит бренд
        if ($form->brand) {
            $query->andWhere(['p.brand_id' => $form->brand]);
        }
        // Если есть категория
        if ($form->category) {
            if ($category = Category::findOne($form->category)) {
                $ids = ArrayHelper::merge([$form->category], $category->getChildren()->select('id')->column());
                $query->joinWith(['categoryAssignments ca'], false);
                $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
            } else {
                $query->andWhere(['p.id' => 0]);
            }
        }
        // Если присутствует текст
        if (!empty($form->text)) {
            $query->andWhere(['or', ['like', 'code', $form->text], ['like', 'name', $form->text]]);
        }
        // Если есть характеристики
        if ($form->values) {
            $productIds = null;
            foreach ($form->values as $value) {
                if ($value->isFilled()) {
                    $q = Value::find()->andWhere(['characteristic_id' => $value->getId()]);

                    $q->andFilterWhere(['>=', 'CAST(value AS SIGNED)', $value->from]);
                    $q->andFilterWhere(['<=', 'CAST(value AS SIGNED)', $value->to]);
                    $q->andFilterWhere(['value' => $value->equal]);
                    // Список айдишников товаров, которые совпали
                    $foundIds = $q->select('product_id')->column();
                    // Ищем пересечения
                    $productIds = $productIds === null ? $foundIds : array_intersect($productIds, $foundIds);
                }
            }
            if ($productIds !== null) {
                // Длинный массив из найденых товаров
                $query->andWhere(['p.id' => $productIds]);
            }
        }


        $query->groupBy('p.id');

        // Формитруем
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['p.name' => SORT_ASC],
                        'desc' => ['p.name' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['p.price_new' => SORT_ASC],
                        'desc' => ['p.price_new' => SORT_DESC],
                    ],
                ],
            ]
        ]);
    }




}