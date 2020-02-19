<?php

use shop\entities\shop\Category;
use shop\forms\shop\CategoryForm;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="category-index">

    <p><?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="box">
    <div class="box-body">
        <i>Вывод регулируем в CategorySearch</i>

        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyCell' => '',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'value'=> function ($data){
                    return
                        Html::a('<span class="glyphicon glyphicon-arrow-down"> </span>', ['move-down', 'id'=>$data->id] ,['data-method' => 'post']).
                        Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', ['move-up', 'id'=>$data->id] ,['data-method' => 'post']);
                },
                'format'=>'raw',
                'headerOptions' => ['width' => '50'],
            ],
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '40'],
            ],
            [
                'attribute'=>'name',
                'contentOptions' => ['class' => 'table_class','style'=>'display:block;'],
                'content' => function($data){
                           $res = ($data->depth > 1 ? str_repeat('-&nbsp;', $data->depth - 1) . '' : '');
                           $name = ($data->depth > 1 ? $data->name : '<strong>'.$data->name.'</strong>');
                    return $res . $name;
                },
            ],



            'slug',
            'title',
            'description:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} &nbsp {update} &nbsp {delete}{link}',
            ],
           // ['class' => ActionColumn::class],
        ],
    ]); ?>
    </div>
</div>
</div>
