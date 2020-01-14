<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\entities\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Для сброса пароля, пройдите по ссылке ниже: </p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
