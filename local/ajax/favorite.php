<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Commerce\Platform\Favorite\FavoriteService;

header('Content-Type: application/json; charset=utf-8');

try {
    if (!check_bitrix_sessid()) {
        throw new RuntimeException('Invalid session.');
    }

    $active = (new FavoriteService())->toggle((int)($_POST['product_id'] ?? 0));

    echo json_encode(['success' => true, 'active' => $active], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Request failed.'], JSON_UNESCAPED_UNICODE);
}
