<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

use Commerce\Platform\Basket\BasketService;

$APPLICATION->SetTitle('Корзина');

$summary = (new BasketService())->summary();
?>
<h1>Корзина</h1>

<div class="basket">
    <?php foreach ($summary['items'] as $item): ?>
        <div class="basket__item">
            <strong><?=htmlspecialcharsbx($item['name'])?></strong>
            <span><?=htmlspecialcharsbx($item['quantity'])?> × <?=htmlspecialcharsbx($item['price'])?> <?=htmlspecialcharsbx($item['currency'])?></span>
            <span><?=htmlspecialcharsbx($item['sum'])?></span>
        </div>
    <?php endforeach; ?>

    <strong>Итого: <?=htmlspecialcharsbx($summary['total'])?></strong>

    <form action="/api/v1/order.php" method="post">
        <input name="phone" placeholder="Телефон" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="address" placeholder="Адрес" required>
        <button type="submit">Оформить заказ</button>
    </form>
</div>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'; ?>
