<?php

use shop\entities\User;
use yii\helpers\Html;
use yii\grid\GridView;
//use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSe4arch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

     <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box">
        <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',

            /* TODO нихуя выборака нормально не работает*/
            [
                'attribute' => 'created_at',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,

                    'name' => 'UserSearch[date_from]',
                    'name2' => 'UserSearch[date_to]',

                    'value' => '',
                    'value2' => '',

                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',

                    'options' => ['placeholder' => 'Start date','class' => 'datepicker'],
                    'options2' => ['placeholder' => 'End date', 'class' => 'datepicker'],

                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ],
                ]),
                'format' => 'datetime',
            ],

            'username',


            //'auth_key',
            //'password_hash',
            //'password_reset_token',
             'email:email',
            //'email_confirm_token:email',
            [
                    'attribute' => 'status',
                    'filter' => \shop\helpers\UserHelper::statusList(),
                    'value' => function(User $user){
                           return \shop\helpers\UserHelper::statusLabel($user->status);
                    },
                    'format' => 'raw',
            ],

            //'updated_at',
            //'verification_token',
            ['class' => ActionColumn::class],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>
