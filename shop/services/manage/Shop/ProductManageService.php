<?php


namespace shop\services\manage\Shop;


use shop\entities\Meta;
use shop\entities\shop\Product\Product;
use shop\entities\shop\Tag;
use shop\forms\shop\Product\ModificationForm;
use shop\forms\shop\Product\PhotosForm;
use shop\forms\shop\Product\PriceForm;
use shop\forms\shop\Product\ProductCreateForm;
use shop\forms\shop\Product\ProductEditForm;
use shop\repositories\Shop\BrandRepository;
use shop\repositories\Shop\CategoryRepository;
use shop\repositories\Shop\ProductRepository;
use shop\repositories\Shop\TagRepository;
use shop\services\TransactionManager;

class ProductManageService
{

    private $products;
    private $brands;
    private $categories;
    private $tags;
    private $transaction;

    public function __construct(
        TransactionManager $transaction,
        ProductRepository $products,
        BrandRepository $brands,
        CategoryRepository $categories,
        TagRepository $tags
    )
    {
        $this->transaction = $transaction;
        $this->products = $products;
        $this->brands = $brands;
        $this->categories = $categories;
        $this->tags = $tags;
    }

    public function activate($id): void
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    public function draft($id): void
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);

    }




    public function create(ProductCreateForm $form): Product
    {
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product = Product::create(
            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );


        $product->setPrice($form->price->new, $form->price->old);

        // Ходит по другим категориям и привязвает походу...
        foreach ($form->categories->others as $otherId) {
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }
        foreach ($form->values as $value) {
            $product->setValue($value->id, $value->value);
        }
        foreach ($form->photos->files as $file) {
            $product->addPhoto($file);
        }
        foreach ($form->tags->existing as $tagId) {
            $tag = $this->tags->get($tagId);
            $product->assignTag($tag->id);
        }

        // Оборачиваем в транзакцию. Но отсюда с базой работать не будем а сделаем отдельный класс
        // Пишем класс который буедт управлять транзакциями
        // Все что внутри - выполнгиться в одной транзакции
        // Используем use для  появления внешних переменных внутри ананимной функции
        $this->transaction->wrap(function () use ($product, $form) {

            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tags->findByName($tagName)) {
                    $tag = Tag::create($tagName, $tagName);
                    $this->tags->save($tag);
                }
                $product->assignTag($tag->id);
            }

            $this->products->save($product);
        });


        return $product;
    }




    public function edit($id, ProductEditForm $form): void
    {
        $product = $this->products->get($id);
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product->edit(
            $brand->id,
            $form->code,
            $form->name,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        // Пробуем исправить ошибку  sql при апдейте
        $product->changeMainCategory($category->id);

        $this->transaction->wrap(function () use ($product, $form) {

            //Удаляем предыдущие категории
            $product->revokeCategories();
            // Удаляенм предыдыщие теги
            $product->revokeTags();
            // Сохраняем продукт
            $this->products->save($product);

            // И присваеиваем новые категории
            foreach ($form->categories->others as $otherId) {
                $category = $this->categories->get($otherId);
                $product->assignCategory($category->id);
            }
            // Проставляем новые значения  атрибутов
            foreach ($form->values as $value) {
                $product->setValue($value->id, $value->value);
            }
            // И добавляем новые теги
            foreach ($form->tags->existing as $tagId) {
                $tag = $this->tags->get($tagId);
                $product->assignTag($tag->id);
            }
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tags->findByName($tagName)) {
                    $tag = Tag::create($tagName, $tagName);
                    $this->tags->save($tag);
                }
                $product->assignTag($tag->id);
            }
            // Еще раз сохраняем
            $this->products->save($product);
        });






//        $product->changeMainCategory($category->id);
//
//        //Удаляем предыдущие категории
//        $product->revokeCategories();
//        // И присваеиваем новые
//        foreach ($form->categories->others as $otherId) {
//            $category = $this->categories->get($otherId);
//            $product->assignCategory($category->id);
//        }
//        // Проставляем новые значения  атрибутов
//        foreach ($form->values as $value) {
//            $product->setValue($value->id, $value->value);
//        }
//
//        // Удаляенм предыдыщие теги
//        $product->revokeTags();
//        // И добавляем новые
//        foreach ($form->tags->existing as $tagId) {
//            $tag = $this->tags->get($tagId);
//            $product->assignTag($tag->id);
//        }
//
//        $this->transaction->wrap(function () use ($product, $form) {
//            foreach ($form->tags->newNames as $tagName) {
//                if (!$tag = $this->tags->findByName($tagName)) {
//                    $tag = Tag::create($tagName, $tagName);
//                    $this->tags->save($tag);
//                }
//                $product->assignTag($tag->id);
//            }
//            $this->products->save($product);
//        });
    }


    public function changePriceAJAX($id, $priceNew): void
    {
        // Возвращает как калсс продукт 
        // shop\entities\shop\Product\Product
        $product = $this->products->get($id);  
        $product->setPriceNew($priceNew);
        $this->products->save($product);
    }


    public function changePrice($id, PriceForm $form): void
    {
        $product = $this->products->get($id);
        $product->setPrice($form->new, $form->old);
        $this->products->save($product);
    }


    # --------------------PHOTO ---------------------------- #
    //  Сэйв релэйшн бихэвиор оборачивает всею эту хуйню в транзакции
    public function addPhotos($id, PhotosForm $form): void
    {
        $product = $this->products->get($id);
        foreach ($form->files as $file) {
            $product->addPhoto($file);
        }
        $this->products->save($product);
    }

    public function movePhotoUp($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoUp($photoId);
        $this->products->save($product);
    }

    public function movePhotoDown($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoDown($photoId);
        $this->products->save($product);
    }

    public function removePhoto($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->removePhoto($photoId);
        $this->products->save($product);
    }

    # -------------------- end PHOTO ---------------------------- #




    #----------------------- Модификации -------------------------------------#
    public function addModification($id, ModificationForm $form): void
    {
        $product = $this->products->get($id);
        $product->addModification(
            $form->code,
            $form->name,
            $form->price
        );
        $this->products->save($product);
    }

    public function editModification($id, $modificationId, ModificationForm $form): void
    {
        $product = $this->products->get($id);
        $product->editModification(
            $modificationId,
            $form->code,
            $form->name,
            $form->price
        );
        $this->products->save($product);
    }

    public function removeModification($id, $modificationId): void
    {
        $product = $this->products->get($id);
        $product->removeModification($modificationId);
        $this->products->save($product);
    }

    public function remove($id): void
    {
        $product = $this->products->get($id);
        $this->products->remove($product);
    }
    #-----------------------emd Модификации -------------------------------------#
    #----------------------------------------------------------------------------#

}