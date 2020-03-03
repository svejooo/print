<?php
namespace shop\repositories;
use shop\entities\shop\Tag;

class TagRepository
{
    public function get($id): Tag
    {
        if (!$tag = Tag::findOne($id)) {
              throw new NotFoundException('Тэг не найден!!!');
           // throw new \DomainException('Тэг не найден!!!');
        }
        return $tag;
    }

    public function findByName($name): ?Tag
    {
        return Tag::findOne(['name' => $name]);
    }

    public function save(Tag $tag): void
    {
        if (!$tag->save()) {
            throw new \RuntimeException('Ошибка сохранения!');
        }
    }

    public function remove(Tag $tag): void
    {
        if(!$tag->delelte() ) {
            throw new \RuntimeException('Ошибка удаления!');
        }
    }


}