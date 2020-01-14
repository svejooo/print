 <?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
if(isset($title))
    $this->title = $title;
else
    $this->title = "Регистрация";
 $this->params['breadcrumbs'][] = $this->title;

 //////////////////////////////////////////
 class KtmBikeFactory {
     public static function createOriginalKTM() {
         // равносильно new KtmBikeFactory();
         return new self();
     }

     public static function createaReplicaKTM() {
         // а тут мы пока не знаем кто такой этот static
         $foo = new static();
     }

 }


 class ChinaBikeFactory extends KtmBikeFactory {
     function __construct()
      {
      }

 }

 $ktm = ChinaBikeFactory::createOriginalKTM(); // тут будет экземпляр Foo
 $irbis = ChinaBikeFactory::createaReplicaKTM(); // тут будет экземпляр Bar


//////////////////////////////////////////




?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Введите свои учетные данные для регистрации и подтвердите указанный емейл :</p>
        <?php
            if(!isset($type) &&  @$type!="admin")
                echo "<p> <a href='signup-for-admin'> Страница регистрации</a> для администраторов сайта </p>";
            else
                echo "<p> <a href='signup'> Страница регистрации</a> для пользователя сайта </p>";

        ?>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?php
                    if( isset($type)  &&  $type=="admin"   ){
                        // Если регается админ;
                        echo $form->field($model, 'username')->textInput(['autofocus' => true]);
                    }
                ?>
                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
