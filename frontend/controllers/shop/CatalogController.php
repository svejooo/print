<?php

namespace frontend\controllers\shop;


use backend\forms\Shop\CategorySearch;
use backend\forms\Shop\ProductSearch;
use shop\entities\shop\Product\Modification;
use shop\entities\shop\Product\Product;
use shop\forms\shop\Product\PhotosForm;
use shop\services\manage\Shop\ProductManageService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller
{

    private $service;
    public $layout = 'catalog';

    public function __construct($id, $module, ProductManageService $service,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }


    public function  actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()->active()->all(),
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
            'pagination' => false,
        ]);

        return $this->render('index');

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