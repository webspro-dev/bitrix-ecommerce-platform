<?php

namespace Commerce\Platform\Order;

use Commerce\Platform\Basket\BasketService;
use Bitrix\Main\Loader;
use Bitrix\Sale\Order;
use Bitrix\Sale\Fuser;

final class OrderService
{
    public function create(array $data): int
    {
        if (!Loader::includeModule('sale')) {
            throw new \RuntimeException('Sale module is unavailable.');
        }

        global $USER;

        if (!$USER->IsAuthorized()) {
            throw new \RuntimeException('Authorization required.');
        }

        (new BasketService())->recalculate();

        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(Fuser::getId(), SITE_ID);

        if ($basket->isEmpty()) {
            throw new \RuntimeException('Basket is empty.');
        }

        $order = Order::create(SITE_ID, (int)$USER->GetID());
        $order->setPersonTypeId((int)($data['person_type_id'] ?? 1));
        $order->setBasket($basket);

        $propertyCollection = $order->getPropertyCollection();

        $this->setProperty($propertyCollection, 'PHONE', $data['phone'] ?? '');
        $this->setProperty($propertyCollection, 'EMAIL', $data['email'] ?? $USER->GetEmail());
        $this->setProperty($propertyCollection, 'ADDRESS', $data['address'] ?? '');

        $result = $order->doFinalAction(true);

        if (!$result->isSuccess()) {
            throw new \RuntimeException(implode('; ', $result->getErrorMessages()));
        }

        $order->setField('CURRENCY', \Bitrix\Currency\CurrencyManager::getBaseCurrency());

        $saveResult = $order->save();

        if (!$saveResult->isSuccess()) {
            throw new \RuntimeException(implode('; ', $saveResult->getErrorMessages()));
        }

        return (int)$order->getId();
    }

    private function setProperty($collection, string $code, string $value): void
    {
        foreach ($collection as $property) {
            if ($property->getField('CODE') === $code) {
                $property->setValue($value);
                return;
            }
        }
    }
}
