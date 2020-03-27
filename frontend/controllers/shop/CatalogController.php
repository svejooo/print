<?php

namespace frontend\controllers\shop;

use backend\forms\Shop\CategorySearch;
use backend\forms\Shop\ProductSearch;

use http\Client;
use shop\entities\Shop\Category;
use shop\entities\shop\Product\Modification;
use shop\entities\shop\Product\Product;
use shop\forms\shop\AddToCartForm;
use shop\forms\shop\Product\PhotosForm;
use shop\forms\shop\ReviewForm;
use shop\forms\shop\Search\SearchForm;
use shop\repositories\asystem\Asystem;
use shop\repositories\NotFoundException;
use shop\repositories\readModels\ProductReadRepository;
use shop\services\manage\Shop\ProductManageService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller
{
    public $layout = 'catalog';

    private $service;
    private $products;
    private $categories;
    private $brands;
    private $tags;

    public function __construct($id, $module, ProductManageService $service, ProductReadRepository $products,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->products = $products;
    }

    /* ----- ПОИСК ------- */
    public function actionSearch()
    {
        $form = new SearchForm();
        $form->load( \Yii::$app->request->queryParams );
        $form->validate();


        $dataProvider = $this->products->search($form);
        return $this->render('search', [
            'dataProvider' => $dataProvider,
            'searchForm' => $form,
        ]);
    }


    public function actionApi($template){
        //$get = \Yii::$app->request->get();
        //var_dump($get);

        $client = new Client();

        //curl_setopt($ch, 	CURLOPT_URL, "http://91.200.224.132:56088/api/".$template."/");
//        $ch = curl_init();
//        curl_setopt($ch, 	CURLOPT_URL, "http://91.200.224.132:56088/api/54046670/");
//        curl_setopt($ch, 	CURLOPT_POST, 1);
//        curl_setopt($ch, 	CURLOPT_POSTFIELDS, http_build_query($_POST) );
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        echo $server_output = curl_exec($ch);
//        curl_close ($ch);

    }


    public function actionProduct($id)
    {
        //if (!$product = $this->products->find($id))
        //  throw new \DomainException('Такого продукта нет в системе');

        /* TODO  все выборки для продуктов перенсти в ProductReadRepository  */
        if ( !$product = Product::find()->active()->andWhere(['id' => $id])->one() )
            throw new \DomainException('Такого продукта нет в системе');

        $this->layout = 'productOnly';


        $cartForm = new AddToCartForm($product);
        $reviewForm = new ReviewForm();

        $tpl = '54046670';
        $asystem = new Asystem;
        if (!$asystem->getForm($tpl))
            throw new \DomainException('Непришло с ' . $asystem->ip);


        return $this->render('product', [
            'product' => $product,
            'cartForm' => $cartForm,
            'reviewForm' => $reviewForm,
            'asystem' => $asystem,
        ]);
    }







    public function actionCategory($id)
    {
        // Находим категорию по id
        if (!$category = Category::findOne($id))
            throw new  NotFoundException('Нет такой категории.');

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

        //$cat = Category::find()->roots()->one();
        return $this->render('category', [
           'dataProvider' => $dataProvider,
           'category'     => $category,
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


    /*  Здесь выведим просто инфу о товаре с формой загрузки картинок */
    public function actionView($id)
    {
        $product = $this->findModel($id);

        $modificationsProvider = new ActiveDataProvider([
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