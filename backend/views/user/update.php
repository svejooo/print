<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model shop\entities\User */

$this->title = 'Редактирование пользователя: ' . $user->username . ' ('.$user->id.')';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->id, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">



    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>


    <?php
        echo   $form->field($model, 'password')
                    ->passwordInput(['maxLength' => true])
                    ->label('Пароль (Если поле оставить пустым, то пароль не поменяется. Из админки можно ставить пароль из 4 символов)');
    ?>


    <?php // $form->field($model, 'role')->dropDownList($model->rolesList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>



</div>
