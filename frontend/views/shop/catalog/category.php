<?php

/* @var $category shop\entities\shop\Category  */

use yii\helpers\Html; ?>

<h1><?= Html::encode($category->name) ?></h1>


<?= $this->render('_subcategories', ['category' => $category]) ?>



<?php if (trim($category->description)): ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= Yii::$app->formatter->asNtext($category->description) ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->render('_products_list', [
    'dataProvider' => $dataProvider
]) ?>


