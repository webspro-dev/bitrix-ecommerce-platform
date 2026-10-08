<?php

if (PHP_SAPI !== 'cli') {
    exit("CLI only.\n");
}

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 2);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Commerce\Platform\Import\CsvImportService;

$file = $argv[1] ?? '';

if (!$file) {
    exit("Usage: php local/scripts/import_products.php data/products.csv\n");
}

$result = (new CsvImportService())->import($file);

echo "Created: {$result['created']}\n";
echo "Updated: {$result['updated']}\n";
echo "Errors: " . count($result['errors']) . "\n";
