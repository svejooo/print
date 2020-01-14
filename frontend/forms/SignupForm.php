<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use shop\entities\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    //public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
             // ['username', 'trim'],
            //['username', 'required'],
            //['username', 'unique', 'targetClass' => '\common\entities\User', 'message' => 'This username has already been taken.'],
            //['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\shop\entities\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 4],
        ];
    }
//
//    /**
//     * Signs user up.
//     *
//     * @return User
//     */
//    public function signup()
//    {
//        if (!$this->validate()) {
//            return null;
//        }
//
//        $user =  User::signup( $this->username, $this->email, $this->password);
//        // Что бы была возможность создавать юзера еще в каких то местах
//        // БЕЗ КОПИПАСТА,
//        // переносим всю это в конструктор User
//
//        //$user->username = $this->username;
//        //$user->email = $this->email;
//        // $user->setPassword($this->password);
//        //$user->generateAuthKey();
//        // $user->generateEmailVerificationToken();
//
//        // было
//        //return $user->save() && $this->sendEmail($user);
//        return $user->save() ? $user : null;
//
//    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
