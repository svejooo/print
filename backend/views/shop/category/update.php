<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $category shop\entities\shop\Category */
/* @var $model \shop\forms\shop\CategoryForm */


$this->title = 'Обновляем категорию: ' . $category->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
