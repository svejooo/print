<?php

/* @var $category shop\entities\shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;

if (!empty( $category->children)){
?>

<div class="panel panel-default">
    <div class="panel-body">

        <?php

            echo  $category->id == "1" ? 'Основные: ' : 'Подкатегории: ';
            //var_dump($category->children);
            foreach ($category->children as $child): ?>
            <a href="<?= Html::encode(Url::to(['category', 'id' => $child->id])) ?>"><?= Html::encode($child->name) ?></a> &nbsp;
        <?php
            endforeach;?>
    </div>
</div>
<?php
}
?>