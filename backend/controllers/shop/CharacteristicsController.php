<?php

namespace backend\controllers\shop;

use shop\forms\shop\CharacteristicForm;
use shop\services\manage\Shop\CharacteristicsManageService;
use Yii;
use shop\entities\shop\Characteristics;
use backend\forms\Shop\CharacteristicsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CharacteristicsController implements the CRUD actions for Characteristics model.
 */
class CharacteristicsController extends Controller
{

    private $service;

    public function __construct($id, $module, CharacteristicsManageService $service, $config = [])
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
                ],
            ],
        ];
    }


    /**
     * Lists all Characteristics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CharacteristicsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Characteristics model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
    Переделываем экшн
     */
//    public function actionCreate()
//    {
//        $model = new Characteristics();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->1render('create', [
//            'model' => $model,
//        ]);
//    }

        public function actionCreate()
        {
            $form = new CharacteristicForm();
            if( $form->load( Yii::$app->request->post())  && $form->validate()){
                try {
                    $char_s = $this->service->create($form);
                    return $this->redirect([ 'view', 'id'=> $char_s->id ]);
                } catch (\DomainException $e){
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }

            return $this->render('create', ['model' => $form]);
        }




    public function actionUpdate($id)
    {
        $characteristic = $this->findModel($id);


        $form = new CharacteristicForm($characteristic);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($characteristic->id, $form);
                return $this->redirect(['view', 'id' => $characteristic->id]);

            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'characteristic' => $characteristic,
        ]);
    }





    /**
     * Deletes an existing Characteristics model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Characteristics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Characteristics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Characteristics::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
