<?php

namespace shop\services\manage;

use shop\entities\User;
use shop\forms\UserCreateForm;
use shop\forms\UserEditForm;


class UserManageSerice
{

    public function create(UserCreateForm $form): User
    {
        //$user = new User();
        $user = User::create( $form->username,  $form->email, $form->password );

        if(!$user->save()){
            throw new \RuntimeException('Saving error.');
        }
        return $user;

    }

    public function edit($id, UserEditForm $form): void
    {
        $user = User::get($id);
        $user->edit(
            $form->email,
            $form->username
        );

        // Меняем пароль, если ОН пришел
        if( !empty( $form->password )){
            if( strlen( $form->password ) < 4 )
                throw new \DomainException('Пароль должен быть не менее 6 символов');

             $user->setPassword( $form->password );

            // echo $user->password_hash;
            // exit;

        }


        if(!$user->save()){
            throw new \RuntimeException('Saving error.');
        }


    }

}