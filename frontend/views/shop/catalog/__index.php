<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="category-index">


    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'emptyCell' => '',
                'columns' => [
                    [
                        'attribute'=>'name',
                        'contentOptions' => ['class' => 'table_class','style'=>'display:block;'],
                        'content' => function($data){
                            $res = ($data->depth > 1 ? str_repeat('-&nbsp;', $data->depth - 1) . '' : '');
                            $name = ($data->depth > 1 ? $data->name : '<strong>'.$data->name.'</strong>');
                            return $res . $name;
                        },
                    ],

                    'description:ntext',
                    [
                        'header'=>'Ссылка',
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['width' => '120'],
                        'template' => '{view}',
                    ],
                    // ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
