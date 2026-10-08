<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

global $USER;

$APPLICATION->SetTitle('Личный кабинет');

if (!$USER->IsAuthorized()) {
    LocalRedirect('/auth/');
}

?>
<h1>Личный кабинет</h1>
<p>Пользователь: <?=htmlspecialcharsbx($USER->GetLogin())?></p>
<p><a href="/personal/orders/">История заказов</a></p>
<p><a href="/favorites/">Избранное</a></p>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'; ?>
