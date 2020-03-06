<?php


namespace shop\forms\shop;


use shop\entities\Shop\Tag;
use yii\base\Model;

class TagForm extends Model
{
    public $id;
    public $name;
    public $slug;

    private $_tag;

    public function __construct(Tag $tag = null, $config = [])
    {
        if ($tag) {
            $this->name = $tag->name;
            $this->slug = $tag->slug;
            $this->_tag = $tag;
        }
        parent::__construct($config);
    }

//    public function rules(): array
//    {
//        return [
//            [['name','slug'], 'required'],
//            [['name','slug'], 'string', 'max' => 255],
//            ['slug', 'match', 'pattern' => '#^[a-z0-9_-]*$#s'],
//            [['name','slug'], 'unique', 'targetClass' => Tag::class]
//        ];
//    }

    // Энта хуйня сделана стандартным крудом. Коментим ее
//    public function rules(): array
//    {
//          return [
//              [['name','slug'], 'required'],
//              [['name','slug'], 'string', 'max' => 255],
//              ['slug', 'match', 'pattern' => '#^[a-z0-9_-]*$#s'],
//              [['name','slug'], 'unique', 'targetClass' => Tag::class]
//          ];
//    }

}