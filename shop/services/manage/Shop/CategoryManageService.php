<?php


namespace shop\services\manage\Shop;


use shop\entities\Meta;
use shop\entities\Shop\Category;
use shop\forms\shop\CategoryForm;
use shop\repositories\Shop\CategoryRepository;

class CategoryManageService
{

    private $categories;

    public function __construct(CategoryRepository $categories)
    {
        $this->categories = $categories;
    }




    public function create(CategoryForm $form): Category
    {
        $parent = $this->categories->get($form->parentId);
        $category = Category::create(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $category->appendTo($parent);
        $this->categories->save($category);
        return $category;
    }

    public function edit($id, CategoryForm $form): void
    {
        //var_dump($form->meta);
        //echo $form->meta->keywords;
        //exit;

        // Исключаем редакртирование корневой категории
        $category = $this->categories->get($id);
        if($category->isRoot()){
            throw new \DomainException('Не могу редактировать корневую директорию');
        }


        $this->assertIsNotRoot($category);
        $category->edit(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,

            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        // Че здесь происходит?
        if ($form->parentId !== $category->parent->id) {
            $parent = $this->categories->get($form->parentId);
            $category->appendTo($parent);
        }
        $this->categories->save($category);
    }

    public function remove($id): void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        $this->categories->remove($category);
    }

    // Проверка, не родительская ли категория
    private function assertIsNotRoot(Category $category): void
    {
        if ($category->isRoot()) {
            throw new \DomainException('Ты чо! Это корневая директория');
        }
    }

    ###############################

    public function  moveUp($id):void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if ($prev = $category->prev){
            $category->insertBefore($prev);
        }
        $this->categories->save($category);
    }

    public function  moveDown($id):void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if ($prev = $category->next){
            $category->insertAfter($prev);
        }
        $this->categories->save($category);
    }


}