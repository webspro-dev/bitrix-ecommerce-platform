<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

global $USER;

if (!$USER->IsAuthorized()) {
    LocalRedirect('/auth/');
}

$ids = (new \Commerce\Platform\Favorite\FavoriteService())->all();

$APPLICATION->SetTitle('Избранное');
?>
<h1>Избранное</h1>
<p>Количество товаров: <?=count($ids)?></p>
<?php if ($ids): ?>
    <p>ID товаров: <?=htmlspecialcharsbx(implode(', ', $ids))?></p>
<?php else: ?>
    <p>Избранное пусто.</p>
<?php endif; ?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'; ?>
