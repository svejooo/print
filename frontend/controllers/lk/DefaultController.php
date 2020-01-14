<?php
namespace frontend\controllers\lk;

use yii\filters\AccessControl;
use yii\web\Controller;


class DefaultController extends Controller
{
    //public $layout = 'cabinet';

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    // тут пишете, что вам надо (редирект)
                    return $action->controller->redirect('/login');
                }
            ],
        ];
    }
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}