<?php

if (PHP_SAPI !== 'cli') {
    exit("CLI only.\n");
}

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 2);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Loader;
use Commerce\Platform\Config;

Loader::includeModule('iblock');
Loader::includeModule('catalog');
Loader::includeModule('sale');

echo "Commerce Platform installation bootstrap\n";
echo "Product iblock: " . Config::PRODUCT_IBLOCK_ID . PHP_EOL;
echo "SKU iblock: " . Config::SKU_IBLOCK_ID . PHP_EOL;
echo "Review IDs in local/modules/commerce.platform/lib/Config.php before production use.\n";
