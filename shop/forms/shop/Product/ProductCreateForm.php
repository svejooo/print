<?php


namespace shop\forms\shop\Product;

use shop\entities\shop\Characteristics;
use shop\entities\shop\Product\Product;
use shop\forms\CompositeForm;
use shop\forms\MetaForm;

/**
 * @property PriceForm $price
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property PhotosForm $photos
 * @property TagsForm $tags
 * @property ValueForm[] $values
 */

class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name;

    // В конструкторе обрабаатываем все мелкие формы


    public function __construct($config = [])
    {
        $this->price = new PriceForm();
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->photos = new PhotosForm();
        $this->tags = new TagsForm();
        $this->values = array_map(function (Characteristics $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristics::find()->orderBy('sort')->all());
        parent::__construct($config);
    }



    public function rules(): array
    {
        return [
            [['brandId', 'code', 'name'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            [['code'], 'unique', 'targetClass' => Product::class],
        ];
    }

//    protected function internalForms(): array
//    {
//        return ['price', 'meta','photos', 'categories', 'tags', 'values'];
//    }
    protected function internalForms(): array
    {
        // TODO: Implement internalForms() method.
        return ['price', 'meta','photos', 'categories', 'tags', 'values'];
    }
}