<?php
namespace shop\services\auth;

use shop\entities\User;
use shop\forms\LoginForm;


class AuthService {

    //private $_user;


    // Логинмся по емейлу
    public function authByEmail($form): User
    {

        $user = User::findByEmail($form->email);

        if( !$user )
            throw new \DomainException('Не найден пользователь, либо не почта не подтверждена!');

        if( !$user->validatePassword($form->password) )
            throw new \DomainException(' Не верный пароль');


        return $user;

    }


    // Логинмся по имени пользователя
    public function authByUsername($form): User
    {

        $user = User::findByUsername($form->username);

        if( !$user )
            throw new \DomainException('Не найден пользователь');

        if( !$user->validatePassword($form->password) )
            throw new \DomainException(' Не верный пароль');

        return $user;

    }




}