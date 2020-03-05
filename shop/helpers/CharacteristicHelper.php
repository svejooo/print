<?php


namespace shop\helpers;


use shop\entities\shop\Characteristics;
use yii\helpers\ArrayHelper;

class CharacteristicHelper
{
    public static function typeList(): array
    {
        return [
            Characteristics::TYPE_STRING => 'String',
            Characteristics::TYPE_INTEGER => 'Integer number',
            Characteristics::TYPE_FLOAT => 'Float number',
            Characteristics::BOOLEAN => 'Boolean',
        ];
    }

    public static function typeName($type): string
    {
        return ArrayHelper::getValue(self::typeList(), $type);
    }
}