<?php

namespace shop\services\auth;

use shop\entities\User;

class NetworkService
{

    public function auth($network, $identity): User
    {
        if($user = User::findByNetworkIdentity($network, $identity) ){
            return $user;
        }

        $user = User::signupByNetwork($network, $identity);
        $thisUser = new User();
        $thisUser->save($user);
        return $user;
    }

    public function attach($id,$network, $identity):void
    {
        if(User::findByNetworkIdentity($network, $identity) ){
            throw new \DomainException('Соц сеть уже зарегана');
        }

        //$user = User::get($id);
        //$thisUser = new User();
        $thisUser = new User();
        // ПОлучаем юзверя по айди
        //$thisUser = User::get($id);
         $thisUser->get($id);

        $thisUser->attachNetwork($network, $identity);
        $thisUser->save();


    }

}