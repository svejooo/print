<?php


namespace shop\forms\shop\Product;


use yii\base\Model;
use yii\web\UploadedFile;

class PhotosForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules(): array
    {
        return [
            ['files', 'each', 'rule' => ['image']],  // Валидируем массив с картинками
           // ['files',  'image'],  // Валидация - Если принимаем одну картинку
        ];
    }

    //public function beforeValidate(): bool
    public function afterValidate(): bool
    {

        if (parent::beforeValidate()) {
            $this->files = UploadedFile::getInstances($this, 'files');
               return true;
        }
        return false;
    }
}