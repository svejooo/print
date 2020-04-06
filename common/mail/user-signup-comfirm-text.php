<?php

/* @var $user \shop\entities\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/signup-confirm', 'token' => $user->email_confirm_token]);
?>

    Пройдите по ссылке, что бы подтвердить свой емейл (confirm text)

<?= $confirmLink ?>