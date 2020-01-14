<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name'=>'Printme - Онлайн типография',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [

        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            // все Auth клиенты будут использовать эту конфигурацию для HTTP клиента:
            'httpClient' => [
                'transport' => 'yii\httpclient\CurlTransport',
            ],
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => 'google_client_id',
                    'clientSecret' => 'google_client_secret',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => 'facebook_client_id',
                    'clientSecret' => 'facebook_client_secret',
                ],
                'vkontakte' => [
                  'class' => 'yii\authclient\clients\VKontakte',
                  'clientId' => '7236732',
                  'clientSecret' => 'SzFNlOI1YOWZfGvjH9gi',
              ],
                // etc.
            ],
            ],

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
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'shop\entities\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
                'signup' => 'auth/signup/signup',
                'signup-for-admin' => 'auth/signup/signup-for-admin',
                'login' => 'auth/auth/login',
                'logout' => 'auth/auth/logout',

            ],
        ],

        // 'backendUrlManager' => require __DIR__ . '/../../backend/config/urlManager.php',
        //'frontendUrlManager' => require __DIR__ . '/urlManager.php',


    ],
    'params' => $params,
];
