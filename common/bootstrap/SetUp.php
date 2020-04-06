<?php

namespace common\bootstrap;


use frontend\services\contact\ContactService;
use yii\base\Application;
use  yii\base\BootstrapInterface;


class SetUp implements BootstrapInterface {

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {

        $container = \Yii::$container;
        $container->setSingleton(ContactService::class,[],['adminEmail'=>'44444444' ]);

        //    $container->set(ContactService::class, [],[[$app->params['adminEmail']]]);
        //$container->set(ContactService::class,[], [ $app->params['adminEmail'] ]);

//         $container->setSingleton(PasswordResetService::class, [], [
//                    [$app->params['adminEmail']=>$app->name  ]
//         ] );

         //$container->setSingleton(ContactService::class,[],[

       /* $container->setSingleton(ContactService::class,[],[
             //$app->params['adminEmail']
             'хуй'
         ]);
       */

//         $container->setSingleton(PasswordResetService::class, [], [
//                    [$app->params['supportEmail']=>$app->name . 'robot' ]
//         ] );

//         $container->setSingleton('user.password_reset', function () use ($app) {
//             return new ();
//         });
    }
}