<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $characteristic \shop\entities\shop\Characteristics */
/* @var $model \shop\forms\shop\CharacteristicForm */


$this->title = 'Update Characteristic: ' . $characteristic->name;
$this->params['breadcrumbs'][] = ['label' => 'Characteristics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $characteristic->name, 'url' => ['view', 'id' => $characteristic->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="characteristic-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
