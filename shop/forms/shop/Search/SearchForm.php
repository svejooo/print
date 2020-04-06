<?php

namespace shop\forms\shop\Search;


use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\Characteristics;
use shop\forms\CompositeForm;
use shop\forms\shop\Search\ValueForm;
use yii\helpers\ArrayHelper;

/**
 * @property ValueForm[] $values
 */

class SearchForm extends CompositeForm
{

    public $text;
    public $category;
    public $brand;

    public function __construct(array $config = [])
    {
        $this->values = array_map(function (Characteristics $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristics::find()->orderBy('sort')->all());
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['text'], 'string'],
            [['category', 'brand'], 'integer'],
        ];
    }

    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function formName(): string
    {
        return '';
    }


    protected function internalForms(): array
    {
        return ['values'];
    }

}