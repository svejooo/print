<?php


namespace backend\controllers\shop;


use backend\forms\Shop\ProductSearch;
use shop\services\manage\Shop\ProductManageService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ProductController extends Controller
{
    private $service;

    public function __construct($id, $module, ProductManageService $service,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete-photo' => ['POST'],
                    'move-photo-up' => ['POST'],
                    'move-photo-down' => ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}