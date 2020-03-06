<?php


namespace shop\forms\shop\Product;


use shop\entities\shop\Characteristics;
use shop\entities\shop\Product\Value;
use yii\base\Model;

/**
 * @property integer $id
 */


class ValueForm extends Model
{
    public $value;

    private $_characteristic;

    public function __construct(Characteristics $characteristic, Value $value = null, $config = [])
    {
        if ($value) {
            $this->value = $value->value;
        }
        $this->_characteristic = $characteristic;
        parent::__construct($config);
    }

    // Валидируем в зависимости от типа данных - ДИНАМИЧЕСКАЯ ВАЛИДАЦИЯ
    public function rules(): array
    {
        return array_filter([

            $this->_characteristic->required ? ['value', 'required'] : false,
            $this->_characteristic->isString() ? ['value', 'string', 'max' => 255] : false,
            $this->_characteristic->isInteger() ? ['value', 'integer'] : false,
            $this->_characteristic->isFloat() ? ['value', 'number'] : false,
            $this->_characteristic->isBool() ? ['value', 'string'] : false,   // TODO - тип данных булевый - нада заменить
            ['value', 'safe'],

        ]);
    }

    public function attributeLabels(): array
    {
        return [
            'value' => $this->_characteristic->name,
        ];
    }

    public function variantsList(): array
    {
        return $this->_characteristic->variants ? array_combine($this->_characteristic->variants, $this->_characteristic->variants) : [];
    }

    public function getId(): int
    {
        return $this->_characteristic->id;
    }
}