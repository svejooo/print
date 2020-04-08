<?php

namespace shop\forms\shop;

use shop\entities\shop\Category;
//use shop\forms\CompositeForm;

use shop\forms\CompositeForm;
use shop\forms\MetaForm;
use shop\validators\SlugValidator;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property MetaForm $meta;
 */
//class CategoryForm extends CompositeForm
class CategoryForm extends CompositeForm
{
    public $name;
    public $slug;
    public $title;
    public $description;
    public $parentId;

//    public $meta;
//    public $meta_json;
//    public $lft;
//    public $rgt;
//    public $depth;

    private $_category;

    public function __construct(Category $category = null, $config = [])
    {

        if ($category) {
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->title = $category->title;

            $this->description = $category->description;
            $this->parentId = $category->parent ? $category->parent->id : null;

            $this->meta = new MetaForm($category->meta);

            //var_dump($this->meta);
            //var_dump($category);
            //exit;
            $this->_category = $category;
        } else {
            $this->meta = new MetaForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['parentId'], 'integer'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['description'], 'string'],
            ['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Category::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null]
        ];
    }

    public function parentCategoriesList(): array
    {
        return ArrayHelper::map(Category::find()->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ( $category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });
    }

    public function internalForms(): array
    {
        return ['meta'];
    }
}