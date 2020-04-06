<?php
namespace frontend\controllers\auth;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use frontend\forms\SignupForm;
use frontend\forms\SignupFormForAdmin;
use shop\services\auth\SignupService;


class SignupController extends Controller
{

      /* TODO  не работает  поведение*/
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],

                ],
            ],

        ];
    }


    public function actionIndex(){
        echo 'Namespace - '. __NAMESPACE__ ;
    }


    /**
     * Signs user up.
     *
     * @return mixed
     */

    public function actionSignupForAdmin()
    {
        $form = new SignupFormForAdmin();

        // Если данные пришли и провалидировались, регаем юзера
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            // Далее через сервисный слой проводим вспомогательные операции:
            // Генерим токен и отправялем его на почту
            $signupService = new SignupService();

            // Пробуем отправить емейл
            try {
                $user = $signupService->signup($form);
                $arrEmaeil = explode('@', $form->email);
                $thisDomain = $arrEmaeil[1];

                Yii::$app->session->setFlash(
                    'success',
                    ' Теперь подтвердите почту.  <br> Перейти на <a href="http://' . $thisDomain . '" target="_blank">' . $thisDomain . '</a>');

                $signupService->sentEmailConfirm($user);
                return $this->goBack();

            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                //Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('signup', [
            'model' => $form, 'type' => 'admin', 'title' => 'Регистрация для администраторов сайта'
        ]);

    }


    public function actionSignup()
    {
        //$form = new SignupFormForAdmin();
        $form = new SignupForm();

        // Если данные пришли и провалидировались, регаем юзера
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            // Далее через сервисный слой проводим вспомогательные операции:
            // Генерим токен и отправялем его на почту
            $signupService = new SignupService();

            // Пробуем отправить емейл
            try {
                $user = $signupService->signup($form);
                $arrEmaeil = explode('@', $form->email);
                $thisDomain = $arrEmaeil[1];

                Yii::$app->session->setFlash(
                    'success',
                    ' Теперь подтвердите почту. На ' . $form->email
                    . ' отправлена ссылка (если не пришло, проверьте спам).  
                            Если письмо не пришло даже в спам, свяжитесь с администрацией через форму обратной связи  <br> 
                            Перейти на <a href="http://' . $thisDomain . '" target="_blank">' . $thisDomain . '</a>');

                $signupService->sentEmailConfirm($user);
                return $this->goBack();

            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                //Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('signup', [
            'model' => $form,
        ]);


        //if($user = $form->signup() ) {
        //Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
    }

    public function actionSignupConfirm($token)
    {

        $signupService = new SignupService();

        try {
            $signupService->confirmation($token);
            Yii::$app->session->setFlash('success', 'Вы подтвердили свой почтовый ящик! <br> 
                                            Теперь вы полноценный участник системы!');
        } catch (\Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        $this->redirect(Yii::$app->urlManager->createUrl("site/login"));

        //self::actionLogin();
        //return $this->actionLogin();
        //return  Yii::$app->getResponse()->redirect('http://printme-frontend.ru/site/login');
        //return $this->goHome();

    }



}