<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Commerce\Platform\Order\OrderService;

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('POST required.');
    }

    global $USER;

    if (!$USER->IsAuthorized()) {
        throw new RuntimeException('Authorization required.');
    }

    $payload = json_decode(file_get_contents('php://input'), true) ?: [];
    $orderId = (new OrderService())->create($payload);

    echo json_encode([
        'success' => true,
        'data' => ['order_id' => $orderId],
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Order creation failed.',
    ], JSON_UNESCAPED_UNICODE);
}
