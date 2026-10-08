<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

$APPLICATION->SetTitle('Каталог');

$APPLICATION->IncludeComponent(
    'commerce:catalog',
    '',
    ['PAGE_SIZE' => 24]
);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
