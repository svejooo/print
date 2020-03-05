<?php


namespace shop\entities\shop\Product;



use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $file
 * @property integer $sort
 */

class Photo extends ActiveRecord
{
    public static function create(UploadedFile $file): self
    {
        $photo = new static();
        $photo->file = $file;
        return $photo;
    }

    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public static function tableName(): string
    {
        return '{{%shop_photos}}';
    }


    public function behaviors(): array
    {
        return [
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                 // путь для загрузки Картинок
                'filePath' => '@common/upload/origin/products/[[attribute_product_id]]/[[id]].[[extension]]',
                 // путь для отображения (урл)
                'fileUrl' => '@common/upload/origin/products/[[attribute_product_id]]/[[id]].[[extension]]',
                // путь для загрузки thumb
                'thumbPath' => '@common/upload/cache/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@common/upload/cache/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',

                //'filePath' => '@staticRoot/origin/products/[[attribute_product_id]]/[[id]].[[extension]]',
                //'fileUrl' => '@static/origin/products/[[attribute_product_id]]/[[id]].[[extension]]',
                //'thumbPath' => '@staticRoot/cache/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',
                //'thumbUrl' => '@static/cache/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                ],
            ],
        ];
    }
}