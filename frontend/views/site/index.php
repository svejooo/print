<?php

/* @var $this yii\web\View */

$this->title = 'PrintMe';

?>
<div class="site-index">

    <div class="jumbotron">
        <?php
              exec('php -v', $response);echo $response[0];
        ?>
        <h1>Congratulations!</h1>
        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>
    <div class="body-content">
        <div class="row">
            <img src="<?= Yii::getAlias('@static/upload/9.jpg')?>" width="100%" />
        </div>
    </div>

</div>
