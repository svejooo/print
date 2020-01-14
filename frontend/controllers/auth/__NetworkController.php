<?php


namespace frontend\controllers\auth;


use yii\authclient\AuthAction;
use yii\web\Controller;
use shop\services\auth\NetworkService;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;


class MYNetworkController extends Controller
{
        public function actions()
        {
           return [
             'auth' => ['class' => AuthAction::class, 'successCallback'=> [$this, 'onAuthSuccess']]
           ];
        }


    public function onAuthSuccess(ClientInterface $client): void
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');
        try {
            $user = $this->service->auth($network, $identity);
            Yii::$app->user->login(new Identity($user), Yii::$app->params['user.rememberMeDuration']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

}