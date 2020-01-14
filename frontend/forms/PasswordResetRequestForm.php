<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use shop\entities\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
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
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Такой пользователь не зарегистрирован, либо адрес почты не подтвержден'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);


        if (!$user)
            return false;
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }


        $sent = Yii::$app->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user])
            ->setTo($user->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Подтверждение регистрации на  printme.ru')
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
            return false;
        }

        return true;
//        return Yii::$app
//            ->mailer
//            ->compose(
//                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
//                ['user' => $user]
//            )
//            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
//            ->setTo($this->email)
//            ->setSubject('Password reset for ' . Yii::$app->name)
//            ->send();
    }
}
