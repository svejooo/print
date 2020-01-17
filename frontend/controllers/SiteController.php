<?php
namespace frontend\controllers;



use frontend\services\contact\ContactService;

//  use shop\services\auth\AuthService;
//use shop\forms\LoginForm;
//use shop\entities\User;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use frontend\forms\SignupForm;
use frontend\forms\SignupFormForAdmin;
use frontend\forms\ContactForm;

use frontend\forms\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;


/**
 * Site controller
 */
class SiteController extends Controller
{

//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout', 'signup'],
//                'rules' => [
//                    [
//                        'actions' => ['signup'],
//                        'allow' => true,
//                        'roles' => ['?'],
//                    ],
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
                'captcha' => [
                    'class' => 'yii\captcha\CaptchaAction',
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }




    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {


            //if ($form->sendEmail(Yii::$app->params['adminEmail'])) {
            if ($form->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $form,
            ]);
        }
    }



    public function actionAbout()
    {
        return $this->render('about');
    }





    //////////////////////////////////////////
    /*TODO  create ResetController in  auth*/
    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $form = new PasswordResetRequestForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if ($form->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою почту.');

                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('error', 'Простите, но мы не можем отправить письмо на указанный адрес. Свяжитесь с администрацией.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $form,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $form = new ResendVerificationEmailForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            if ($form->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою почту и следуйте инструкциям.');
                return $this->goBack();
            }
            else
                Yii::$app->session->setFlash('error', 'Не можем отправить письмо на '.$form->email );
        }

        return $this->render('resendVerificationEmail', [
            'model' => $form
        ]);
    }
}
