<?php


namespace shop\entities\shop;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $name
 * @property integer $slug
 * */

class Tag extends ActiveRecord
{


    public static function create($name, $slug): self
    {
        $tag  = new static();
        $tag->name = $name;
        $tag->slug = $slug;
        return $tag;
    }

    public function edit($name, $slug): void
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    public static function tableName()
    {
       return '{{%shop_tags}}';
    }

    public function delelte()
    {
        exit('хуй там');
    }

}