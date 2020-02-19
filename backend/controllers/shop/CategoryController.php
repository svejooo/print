<?php

namespace backend\controllers\shop;



use shop\entities\shop\Category;
use shop\forms\shop\CategoryForm;
use shop\services\manage\Shop\CategoryManageService;
use Yii;
use backend\forms\Shop\CategorySearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{

    private $manageService;


    public function __construct($id, $module, CategoryManageService $manageService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->manageService = $manageService;
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Brand models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'category' => $this->findModel($id),
        ]);
    }



    /**
     * Creates a new Brand model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new CategoryForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $category = $this->manageService->create($form);
                return $this->redirect(['view', 'id' => $category->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }


    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */

    public function actionUpdate($id)
    {
        $category = $this->findModel($id);
        $form = new CategoryForm($category);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->manageService->edit($category->id, $form);
                return $this->redirect(['view', 'id' => $category->id]);

            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }


        //  Отображение
        return $this->render('update', [
            'model' => $form,
            'category' => $category,
        ]);
    }


    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->manageService->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Category
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Запрашиваемая стр не нашлась...');
    }


    ###############################
    public function actionMoveUp($id)
    {
        $this->manageService->moveUp($id);
        return $this->redirect(['index']);
    }

    public function actionMoveDown($id)
    {
        $this->manageService->moveDown($id);
        return $this->redirect(['index']);
    }

}
