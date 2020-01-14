<?php
namespace frontend\controllers\auth;

use Yii;
use yii\web\Controller;
use shop\forms\LoginForm;
use shop\services\auth\AuthService;

class AuthController extends Controller
{

     public function actionIndex(){
         echo 'Контроллер авторизации <br>';
         echo  __NAMESPACE__;

     }

    /**  Logs in a user
    @return mixed  */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        $form = new LoginForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            // Сервисный слой
            $user = new AuthService();
            try{
                $user = $user->authByEmail($form);

                Yii::$app->user->login($user, $form->rememberMe ? 3600 * 24 * 30 : 0);
                return $this->goBack();
            } catch (\DomainException $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }


        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}