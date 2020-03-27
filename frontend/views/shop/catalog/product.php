<?php

//var_dump($product);
/* @var $this yii\web\View */
/* @var $product shop\entities\shop\Product\Product */
/* @var $cartForm shop\forms\shop\AddToCartForm */
/* @var $reviewForm shop\forms\shop\ReviewForm */
/* @var $asystem shop\repositories\asystem\Asystem */


use shop\helpers\PriceHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $product->name;

/* TODO  не выводяться подкатегории в хлебных крошках (евробуклеты) */
$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
foreach ($product->category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'id' => $product->category->id]];
$this->params['breadcrumbs'][] = $product->name;
$this->params['active_category'] = $product->category;
?>
<script>

    document.cookie = "asystemTpl=<?= $asystem->tpl ?>";
</script>
<div class="row" xmlns:fb="http://www.w3.org/1999/xhtml">
    <div class="col-sm-8">
        <ul class="thumbnails">
            <?php foreach ($product->photos as $i => $photo): ?>
                <?php if ($i == 0): ?>
                    <li>
                        <a class="thumbnail" target="_blank" href="<?= $photo->getThumbFileUrl('file', 'thumb') ?>">
                            <img src="<?= $photo->getThumbFileUrl('file', 'catalog_list') ?>" alt="<?= Html::encode($product->name) ?>" />
                        </a>
                    </li>
                <?php else: ?>
                    <li class="image-additional">
                        <a class="thumbnail" target="_blank" href="<?= $photo->getThumbFileUrl('file', 'thumb') ?>">
                            <img src="<?= $photo->getThumbFileUrl('file', 'catalog_list') ?>" />
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-description" data-toggle="tab">Описание</a></li>
            <li><a href="#tab-specification" data-toggle="tab">Спецификация</a></li>
            <li><a href="#tab-review" data-toggle="tab">Отзывы</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab-description"><p>
                    <?= Yii::$app->formatter->asNtext($product->description) ?>
                    <?php echo  $asystem->asystemForm; ?>
            </div>
            <div class="tab-pane" id="tab-specification">
                <table class="table table-bordered">
                    <tbody>
                    <?php foreach ($product->values as $value): ?>
                        <?php if (!empty($value->value)): ?>
                            <tr>
                                <th><?= Html::encode($value->characteristic->name) ?></th>
                                <td><?= Html::encode($value->value) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="tab-review">
                <div id="review"></div>
                <h2>Write a review</h2>

                <?php if (Yii::$app->user->isGuest): ?>

                    <div class="panel-panel-info">
                        <div class="panel-body">
                            Нужно <?= Html::a('авторизоваться', ['/auth/auth/login']) ?>, чтобы оставить отзыв.
                        </div>
                    </div>

                <?php else: ?>

                    <?php $form = ActiveForm::begin(['id' => 'form-review']) ?>

                    <?= $form->field($reviewForm, 'vote')->dropDownList($reviewForm->votesList(), ['prompt' => '--- Select ---']) ?>
                    <?= $form->field($reviewForm, 'text')->textarea(['rows' => 5]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Send', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
                    </div>

                    <?php ActiveForm::end() ?>

                <?php endif; ?>

            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <p class="btn-group">
            <a href="view?id=<?= $product->id ?>"   type="button"  class="btn btn-success">Добавить макет</a>
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="Add to Wish List" onclick="wishlist.add('47');"><i class="fa fa-heart"></i></button>
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="Compare this Product" onclick="compare.add('47');"><i class="fa fa-exchange"></i></button>
        </p>
        <h1><?= Html::encode($product->name) ?></h1>
        <ul class="list-unstyled">
            <li>Brand: <a href="<?= Html::encode(Url::to(['brand', 'id' => $product->brand->id])) ?>"><?= Html::encode($product->brand->name) ?></a></li>
            <li>
                Tags:
                <?php foreach ($product->tags as $tag): ?>
                    <a href="<?= Html::encode(Url::to(['tag', 'id' => $tag->id])) ?>"><?= Html::encode($tag->name) ?></a>
                <?php endforeach; ?>
            </li>
            <li>Product Code: <?= Html::encode($product->code) ?></li>
        </ul>
        <ul class="list-unstyled">
            <li>
                <h2><?= PriceHelper::format($product->price_new) ?></h2>
            </li>
        </ul>
        <div id="product">
            <hr>
<!--            <h3>Available Options</h3>-->

            <?php $form = ActiveForm::begin() ?>
            <?= $form->field($cartForm, 'modification')->label('Модификации')->dropDownList($cartForm->modificationsList(), ['prompt' => '--- Select ---']) ?>
            <?= $form->field($cartForm, 'quantity')->label('Кол-во')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Добавить в корзину', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
            </div>

            <?php ActiveForm::end() ?>
        </div>
        <div class="rating">
            <p>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">0 Отзывов</a> /
                <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">Написать отзыв</a></p>
            <hr>
        </div>
    </div>
</div>


<!--
<script type="text/javascript">
    $('#button-cart').on('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-cart').button('loading');
            },
            complete: function() {
                $('#button-cart').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            var element = $('#input-option' + i.replace('_', '-'));

                            if (element.parent().hasClass('input-group')) {
                                element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            } else {
                                element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            }
                        }
                    }

                    if (json['error']['recurring']) {
                        $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('has-error');
                }

                if (json['success']) {
                    $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                    $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');

                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
</script>
-->

