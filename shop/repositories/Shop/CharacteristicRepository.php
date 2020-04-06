<?php


namespace shop\repositories\Shop;


use shop\entities\Shop\Characteristics;
use shop\repositories\NotFoundException;

class CharacteristicRepository
{

    public function get($id): Characteristics
    {
        if (!$characteristic = Characteristics::findOne($id)) {
            throw new NotFoundException('Characteristic is not found.');
        }
        return $characteristic;
    }

    public function save(Characteristics $characteristic): void
    {
        if (!$characteristic->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Characteristics $characteristic): void
    {
        if (!$characteristic->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

}