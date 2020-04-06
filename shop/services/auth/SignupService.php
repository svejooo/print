<?php
namespace  shop\services\auth;

//use common\models\User;
//use frontend\forms\SignupForm;
use shop\entities\User;

use Yii;
use yii\base\Exception;


class SignupService
{
    //  public function signup(SignupForm $form): User
    public function signup($form)
    {
        $user = new User();

        if(isset($form->username))
            $user->username = $form->username;
        else
            $user->username = "";

        $user->generateAuthKey();
        $user->setPassword($form->password);
        $user->email = $form->email;
        $user->email_confirm_token = Yii::$app->security->generateRandomString();
        $user->status = User::STATUS_WAIT;


        if(!$user->save()){
            throw new \RuntimeException('Saving error.');
        }


        return $user;

        // ПО умолчанию ставим роль АВТОР
        //$userRole = Yii::$app->authManager->getRole('author');
        //Yii::$app->authManager->assign($userRole, $user->getId()); // Для конкретного пользователя


    }

    public function confirm($token): void
    {
        if(empty($token))
            throw new \DomainException('Блин, ну пустой токен то');

        $user = User::findOne(['email_confirm_token' => $token]);
        if(!$user)
            throw new \DomainException('Такого пользователя у нас нету! ');

        $user->confirmSignup();

        if(!$user->save())
            throw new \RuntimeException("Ошибка сохранения");

    }

    public function sentEmailConfirm(User $user)
    {
        $email = $user->email;
        $sent = Yii::$app->mailer
            ->compose(
                ['html' => 'user-signup-comfirm-html'],
                ['user' => $user])
            ->setTo($email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Подтверждение регистрации на  printme.ru')
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }
    }

    public function confirmation($token)
    {
        if (empty($token)) {
            throw new \DomainException('Пришел пустой токен... Странно.');
        }

        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user) {
            throw new \DomainException('Пользователь не найден ... Проверьте правильность емейла или зарегистрируйтесь заново');
        }

        // Если все заэбись, обнАляем токен и меняем статус
        $user->email_confirm_token = null;
        $user->status = User::STATUS_ACTIVE;

        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }

        if (!Yii::$app->getUser()->login($user)){
            throw new \RuntimeException('Error authentication.');
        }


        // Даем разрещение на оставление отзывов
        //$permit = Yii::$app->authManager->getPermission('comments.create');
        //Yii::$app->authManager->assign($permit, $user->getId());
    }

}