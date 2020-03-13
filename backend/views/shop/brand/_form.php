<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model shop\entities\shop\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brand-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <?php // $form->field($model->meta, 'title')->textInput() ?>
            <?php echo $form->field($model->meta, 'title')->textInput() ?>
            <?php // $form->field($model->meta, 'description')->textarea(['rows' => 2]) ?>
            <?php echo $form->field($model->meta, 'description')->textarea(['rows' => 2]) ?>
            <?php // $form->field($model->meta, 'keywords')->textInput() ?>
            <?php echo $form->field($model->meta, 'keywords')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
