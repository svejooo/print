<?php
use yii\helpers\Html;

/* @var $user \shop\entities\User */

// ГЕНЕРИМ ССЫЛКУ !!!
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/signup-confirm', 'token' => $user->email_confirm_token]);

?>
<div class="password-reset">
    <p>Пройдите по ссылке ниже, чтобы активировать аккаунт!</p>
    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>