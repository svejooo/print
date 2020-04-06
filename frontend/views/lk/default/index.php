<?php
use yii\helpers\Html;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="cabinet-index">
    <p>Двуличный кабинет</p>

    <h2>Добавить профиль для авторизации</h2>
    <?php
        echo  $_SERVER['REMOTE_ADDR'];
        echo '<br>';
    ?>
    Добавить функцию User->attachNetwork
    Здесь соц сети только добавляем
    <?= yii\authclient\widgets\AuthChoice::widget([
        'baseAuthUrl' => ['lk/network/attach'],
        //'popupMode' => false,
    ]) ?>
</div>


