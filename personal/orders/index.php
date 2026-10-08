<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

global $USER;

if (!$USER->IsAuthorized()) {
    LocalRedirect('/auth/');
}

$APPLICATION->SetTitle('Мои заказы');

$result = \Bitrix\Sale\Order::getList([
    'select' => ['ID', 'DATE_INSERT', 'PRICE', 'CURRENCY', 'STATUS_ID'],
    'filter' => ['=USER_ID' => (int)$USER->GetID()],
    'order' => ['DATE_INSERT' => 'DESC'],
]);

?>
<h1>Мои заказы</h1>
<table>
    <thead><tr><th>№</th><th>Дата</th><th>Сумма</th><th>Статус</th></tr></thead>
    <tbody>
    <?php while ($order = $result->fetch()): ?>
        <tr>
            <td><?=htmlspecialcharsbx($order['ID'])?></td>
            <td><?=htmlspecialcharsbx($order['DATE_INSERT'])?></td>
            <td><?=htmlspecialcharsbx($order['PRICE'])?> <?=htmlspecialcharsbx($order['CURRENCY'])?></td>
            <td><?=htmlspecialcharsbx($order['STATUS_ID'])?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'; ?>
