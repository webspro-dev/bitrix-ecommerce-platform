<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Commerce\Platform\Catalog\CatalogService;
use Commerce\Platform\Logger;

header('Content-Type: application/json; charset=utf-8');

try {
    $result = (new CatalogService())->getProducts([
        'page' => (int)($_GET['page'] ?? 1),
        'limit' => (int)($_GET['limit'] ?? 24),
        'q' => $_GET['q'] ?? '',
        'brand' => $_GET['brand'] ?? '',
        'sort' => $_GET['sort'] ?? 'name',
    ]);

    echo json_encode([
        'success' => true,
        'data' => $result,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    Logger::error('Products API error', ['message' => $e->getMessage()]);
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Internal server error.',
    ], JSON_UNESCAPED_UNICODE);
}
