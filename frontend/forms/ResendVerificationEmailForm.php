<?php
namespace frontend\forms;

//use frontend\services\auth\SignupService;
use shop\services\auth\SignupService;
use Yii;
use shop\entities\User;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\shop\entities\User',
                'filter' => ['status' => User::STATUS_WAIT],
                'message' => 'В системе нет  пользователя с таким НЕПОДТВЕРЖДЕННЫМ email адресом'
            ],
        ];
    }

    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        $user = User::findOne([
            'email' => $this->email,
            'status' => User::STATUS_WAIT
        ]);

        //var_dump($user);
        //exit;

        if ($user === null) {
            return false;
        }


        SignupService::sentEmailConfirm($user);

        return true;

//        $sent = Yii::$app->mailer
//            ->compose(
//                ['html' => 'user-signup-comfirm-html', 'text' => 'user-signup-comfirm-text'],
//                ['user' => $user])
//            ->setTo($email)
//            ->setFrom(Yii::$app->params['adminEmail'])
//            ->setSubject('Подтверждение регистрации на  printme.ru')
//            ->send();
//
//        if (!$sent) {
//            throw new \RuntimeException('Sending error.');
//        }
//        else
//            return true;

//        return Yii::$app
//            ->mailer
//            ->compose(
//                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
//                ['user' => $user]
//            )
//            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
//            ->setTo($this->email)
//            ->setSubject('Account registration at ' . Yii::$app->name)
//            ->send();

    }
}
