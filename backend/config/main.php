<?php

use shop\entities\User;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);



return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'name' => '_PrintMe_',
     // Изменил
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp'
    ],

    'modules' => [],
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'ssl://smtp.yandex.com',
                'username' => 'proproshckin@yandex.ru',
                'password' => 'Gar00501',
                'port' => '465',
                //'encryption' => 'SSL', // у яндекса SSL
            ],
            'useFileTransport' => false, // будем отправлять реальные сообщения

        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'shop\entities\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],


//        'view' => [
//            'theme' => [
//                'pathMap' => [
//                    '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
//                ],
//            ],
//        ],

//        'assetManager' => [
//            'bundles' => [
//                'dmstr\web\AdminLteAsset' => [
//                    'skin' => 'skin-red',
//                ],
//            ],
//        ],

    ],
    'params' => $params,
];
