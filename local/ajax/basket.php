<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Commerce\Platform\Basket\BasketService;

header('Content-Type: application/json; charset=utf-8');

try {
    if (!check_bitrix_sessid()) {
        throw new RuntimeException('Invalid session.');
    }

    $action = (string)($_POST['action'] ?? '');
    $service = new BasketService();

    $data = match ($action) {
        'add' => $service->add(
            (int)($_POST['product_id'] ?? 0),
            (int)($_POST['quantity'] ?? 1)
        ),
        'remove' => $service->remove((int)($_POST['basket_id'] ?? 0)),
        'update' => $service->updateQuantity(
            (int)($_POST['basket_id'] ?? 0),
            (float)($_POST['quantity'] ?? 1)
        ),
        'recalculate' => $service->recalculate(),
        'coupon' => $service->applyCoupon((string)($_POST['coupon'] ?? '')),
        'delete_coupon' => $service->deleteCoupon((string)($_POST['coupon'] ?? '')),
        default => throw new InvalidArgumentException('Unknown action.'),
    };

    echo json_encode([
        'success' => true,
        'data' => $data,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Request failed.',
    ], JSON_UNESCAPED_UNICODE);
}
