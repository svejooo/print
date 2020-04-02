<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

use yii\helpers\Url;


?>

<div class="row">
    <div class="col-md-6">
    <?php
    $values = [
        '' => 'Default',
        'name' => 'Name A-Я',
        '-name' => 'Name Я-А',
        'price' => 'Price >',
        '-price' => 'Price <',
        'rating' => 'Rating >',
        '-rating' => 'Rating <',
    ];

    $current = Yii::$app->request->get('sort');
    foreach($values as $value => $label)
         echo "<a href='" . Url::current(['sort' => $value ]) . "'>.$label.</a><br>";
    ?>
    </div>
    <div class="col-md-6">
        <?php
        $values = [ 5, 10 , 25, 50, 100];
        foreach($values as $value)
            echo "<a href='" . Url::current(['per-page' => $value ]) . "'>$value</a> - ";
        ?>
    </div>
</div>

<div class="row">
<div class="col-md-2 col-sm-6 hidden-xs">
    <div class="btn-group btn-group-sm">
        <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="List"><i class="fa fa-th-list"></i></button>
        <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="Grid"><i class="fa fa-th"></i></button>
    </div>
</div>

<div class="col-md-4 col-xs-6">
    <div class="form-group input-group input-group-sm">
        <label class="input-group-addon" for="input-sort">Сортировка по :</label>
        <select id="input-sort" class="form-control" onchange="location = this.value;">
            <option value="/index.php?route=product/category&amp;path=20&amp;sort=p.sort_order&amp;order=ASC" selected="selected">Default</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;sort=pd.name&amp;order=ASC">Name (A - Z)</option>
            <option value="/index.php?route=product/categvory&amp;path=20&amp;sort=pd.name&amp;order=DESC">Name (Z - A)</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;sort=p.price&amp;order=ASC">Price (Low &gt; High)</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;sort=p.price&amp;order=DESC">Price (High &gt; Low)</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;sort=rating&amp;order=DESC">Rating (Highest)</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;sort=rating&amp;order=ASC">Rating (Lowest)</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;sort=p.model&amp;order=ASC">Model (A - Z)</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;sort=p.model&amp;order=DESC">Model (Z - A)</option>
        </select>
    </div>
</div>
<div class="col-md-3 col-xs-6">
    <div class="form-group input-group input-group-sm">
        <label class="input-group-addon" for="input-limit">Show:</label>
        <select id="input-limit" class="form-control" onchange="location = this.value;">
            <option value="/index.php?route=product/category&amp;path=20&amp;limit=15" selected="selected">15</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;limit=25">25</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;limit=50">50</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;limit=75">75</option>
            <option value="/index.php?route=product/category&amp;path=20&amp;limit=100">100</option>
        </select>
    </div>
</div>
</div>

<div class="row">

</div>
<hr>
<div class="row">
    <?php foreach ($dataProvider->getModels() as $product): ?>
        <?= $this->render('_product', [
            'product' => $product
        ]) ?>
    <?php endforeach; ?>
</div>
<div class="row">
    <div class="col-sm-6 text-left"></div>
    <div class="col-sm-6 text-right">

            Навигация по страницам
            <?php
                     $links = $dataProvider->getPagination()->getLinks();
                     echo isset($links['prev']) ? '<a href="'. $links['prev'] . '"><- Назад </a>' : '';
                     echo isset($links['next']) ? '<a href="'. $links['next'] . '">Вперед -> </a>' : '';

                 //echo '<a href="'. $links['next'] . '">Вперед -> </a>';
               // var_dump($links);
            ?>
    </div>
    <div class="col-sm-6 text-right">Показано товаров <?= $dataProvider->getCount() ?> из <?= $dataProvider->getTotalCount() ?></div>
</div>

