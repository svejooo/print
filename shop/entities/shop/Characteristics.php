<?php

namespace shop\entities\shop;

use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "shop_characteristics".
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $required
 * @property string $default
 * @property array $variants_json
 * @property int $sort
 */
class Characteristics extends ActiveRecord
{

    const TYPE_STRING  = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT   = 'float';
    const BOOLEAN   = 'bool';

    public $variants;

    public static function create($name, $type, $required, $default, array $variants_json, $sort): self
    {
        $object = new static();
        $object->name = $name;
        $object->type = $type;
        $object->required = $required;
        $object->default = $default;
        $object->variants = $variants_json;
        $object->sort = $sort;
        return $object;
    }

    public function edit($name, $type, $required, $default, array $variants_json, $sort): void
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->default = $default;
        $this->variants = $variants_json;
        $this->sort = $sort;
    }

    // Приходит в джейсоне, поэтому нада перегонять
    public function afterFind(): void
    {
        $this->variants = Json::decode($this->getAttribute('variants_json'));
        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('variants_json', Json::encode($this->variants));
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_characteristics';
    }

    /**
     * {@inheritdoc}
     */
    // Это переписываем в CharacteristicForm
    public function _______rules()
    {
        return [
            [['name', 'type', 'required', 'variants_json', 'sort'], 'required'],
            [['required', 'sort'], 'integer'],
            [['variants_json'], 'safe'],
            [['name', 'default'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'type' => 'Тип',
            'required' => 'Required',
            'default' => 'По умолчанию',
            'variants_json' => 'Variants Json',
            'sort' => 'Sort',
        ];
    }

    ######################################
    // Вспомогательные методы
    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public function isBool(): bool
    {
        return $this->type === self::BOOLEAN;
    }

    public function isSelect(): bool
    {
        return count($this->variants) > 0;
    }
}
