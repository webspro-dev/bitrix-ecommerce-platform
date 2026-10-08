<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<section class="catalog" data-catalog>
    <form class="catalog__filter" method="get" data-filter-form>
        <input name="q" value="<?=htmlspecialcharsbx($_GET['q'] ?? '')?>" placeholder="Поиск">
        <input name="brand" value="<?=htmlspecialcharsbx($_GET['brand'] ?? '')?>" placeholder="Бренд">
        <input name="min_price" value="<?=htmlspecialcharsbx($_GET['min_price'] ?? '')?>" placeholder="Цена от">
        <input name="max_price" value="<?=htmlspecialcharsbx($_GET['max_price'] ?? '')?>" placeholder="Цена до">

        <select name="sort">
            <option value="name">По названию</option>
            <option value="price_asc">Сначала дешёвые</option>
            <option value="price_desc">Сначала дорогие</option>
        </select>

        <button type="submit">Применить</button>
    </form>

    <div class="catalog__grid" data-products>
        <?php foreach ($arResult['items'] as $item): ?>
            <article class="product-card">
                <h2><?=htmlspecialcharsbx($item['name'])?></h2>
                <div class="product-card__price">
                    <?=htmlspecialcharsbx($item['price'] ?? 'Цена по запросу')?>
                    <?=htmlspecialcharsbx($item['currency'])?>
                </div>
                <a href="<?=htmlspecialcharsbx($item['url'])?>">Подробнее</a>
                <button
                    type="button"
                    data-add-to-cart
                    data-product-id="<?=$item['id']?>"
                >
                    В корзину
                </button>
                <button
                    type="button"
                    data-favorite
                    data-product-id="<?=$item['id']?>"
                >
                    В избранное
                </button>
            </article>
        <?php endforeach; ?>
    </div>
</section>
