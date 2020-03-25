<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>PrintMe</h1>
        <p class="lead">You have successfully created your Yii-powered application.</p>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h2><a href="/user/">Пользователи</a> </h2>

                <div class="row">
                    <img src="<?= Yii::getAlias('@static/upload/9.jpg')?>" width="100%" />
                </div>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>
    </div>
</div>
