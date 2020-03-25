<?php

namespace frontend\controllers\shop;


use backend\forms\Shop\CategorySearch;
use backend\forms\Shop\ProductSearch;
use Com\Tecnick\Barcode\Barcode;
use shop\entities\Shop\Category;
use shop\entities\shop\Product\Modification;
use shop\entities\shop\Product\Product;
use shop\forms\shop\Product\PhotosForm;
use shop\repositories\NotFoundException;
use shop\services\manage\Shop\ProductManageService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller
{

    private $service;


    public $layout = 'catalog';
    private $products;
    private $categories;
    private $brands;
    private $tags;


    public function __construct($id, $module, ProductManageService $service,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionProduct($id)
    {
        //if (!$product = $this->products->find($id))
        //  throw new \DomainException('Такого продукта нет в системе');

        if ( !$product = Product::find()->active()->andWhere(['id' => $id])->one() )
            throw new \DomainException('Такого продукта нет в системе');

        $this->layout = 'blank';

        $cartForm = new AddToCartForm($product);
        $reviewForm = new ReviewForm();

        return $this->render('product', [
            'product' => $product,
            //'cartForm' => $cartForm,
            //'reviewForm' => $reviewForm,
        ]);
    }




    public function actionCategory($id)
    {
        // Находим категорию по id
        if (!$category = Category::findOne($id)) {
            throw new  NotFoundException('Нет такой категории.');
            //throw new \DomainException('Нет такой категории.');
        }

        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category');
        $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
        $query->joinWith(['categoryAssignments ca'], false);
        $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
        $query->groupBy('p.id');
       //return $this->getProvider($query);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' =>[
                    'id',
                    'name',
                    'price' => [
                        'asc' => ['price_new' => SORT_ASC],
                        'desc' => ['price_new' => SORT_DESC],
                    ]
                ]
            ],
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);


        $cat = Category::find()->roots()->one();
        return $this->render('index', [
           'dataProvider' => $dataProvider,
           'category'     => $cat,
        ]);

    }



    public function  actionIndex()
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'query' => Product::find()->active()->all(),
            //'query' => $product->getModifications()->orderBy('name'),
            'sort' => [
               'defaultOrder' => ['id' => SORT_DESC],
               'attributes' =>[
                   'id',
                   'name',
                   'price' => [
                       'asc' => ['price_new' => SORT_ASC],
                       'desc' => ['price_new' => SORT_DESC],
                   ]
               ]
            ],
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $cat = Category::find()->roots()->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category'     => $cat,
        ]);

        //$searchModel = Product::find()->active()->all();
        //var_dump( $searchModel[0]);
    }


//    public function  actionIndex()
//    {
//
//        $searchModel = new CategorySearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }


    public function actionView($id)
    {
        $product = $this->findModel($id);

        $modificationsProvider = new ActiveDataProvider([
            //'query' => $product->getModifications()->orderBy('name'),
            'query' => $product->getModifications()->orderBy('name'),
            'key' => function (Modification $modification) use ($product) {
                return [
                    'product_id' => $product->id,
                    'id' => $modification->id,
                ];
            },
            'pagination' => false,
        ]);


        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($product->id, $photosForm);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'product' => $product,
            'modificationsProvider' => $modificationsProvider,
            'photosForm' => $photosForm,
        ]);
    }


    #########################################################################
    protected function findModel($id): Product
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }






}