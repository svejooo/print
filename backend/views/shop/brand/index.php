<?php

use shop\entities\shop\Brand;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\BrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Brands';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Brand', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            //
             [ 'attribute' => 'name',
               'value' => function (Brand $model){
                        return Html::a(Html::encode($model->name), ['view', 'id'=> $model->id]  );
               },
                 'format' => 'raw',
             ],
            //'name',

            'slug',
            //'meta_json',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
