<?php

namespace Commerce\Platform\Basket;

use Commerce\Platform\Pricing\PriceService;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;

final class BasketService
{
    private function basket(): Basket
    {
        if (!Loader::includeModule('sale')) {
            throw new \RuntimeException('Sale module is unavailable.');
        }

        return Basket::loadItemsForFUser(Fuser::getId(), SITE_ID);
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $basket = $this->basket();
        $quantity = max(1, $quantity);

        $item = $basket->getExistsItem('catalog', $productId);

        if ($item) {
            $item->setField('QUANTITY', (float)$item->getQuantity() + $quantity);
        } else {
            $item = $basket->createItem('catalog', $productId);
            $item->setFields([
                'QUANTITY' => $quantity,
                'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => SITE_ID,
                'PRODUCT_PROVIDER_CLASS' => '\\CCatalogProductProvider',
            ]);
        }

        $this->applyPrice($item, $productId);
        $this->save($basket);
    }

    public function remove(int $basketId): void
    {
        $basket = $this->basket();
        $item = $basket->getItemById($basketId);

        if (!$item) {
            throw new \InvalidArgumentException('Basket item not found.');
        }

        $item->delete();
        $this->save($basket);
    }

    public function updateQuantity(int $basketId, float $quantity): void
    {
        $basket = $this->basket();
        $item = $basket->getItemById($basketId);

        if (!$item) {
            throw new \InvalidArgumentException('Basket item not found.');
        }

        $item->setField('QUANTITY', max(1, $quantity));
        $this->save($basket);
    }

    public function applyCoupon(string $coupon): array
    {
        if (!Loader::includeModule('sale')) {
            throw new \RuntimeException('Sale module is unavailable.');
        }

        $basket = $this->basket();
        $discount = \Bitrix\Sale\DiscountCouponsManager::getInstance();
        $discount->add($coupon);

        $this->save($basket);

        return $this->summary();
    }

    public function deleteCoupon(string $coupon): array
    {
        $manager = \Bitrix\Sale\DiscountCouponsManager::getInstance();
        $manager->delete($coupon);

        return $this->summary();
    }

    public function recalculate(): array
    {
        $basket = $this->basket();

        foreach ($basket as $item) {
            $this->applyPrice($item, (int)$item->getProductId());
        }

        $this->save($basket);

        return $this->summary();
    }

    public function summary(): array
    {
        $basket = $this->basket();
        $items = [];

        foreach ($basket as $item) {
            $items[] = [
                'id' => (int)$item->getId(),
                'product_id' => (int)$item->getProductId(),
                'name' => $item->getField('NAME'),
                'quantity' => (float)$item->getQuantity(),
                'price' => (float)$item->getPrice(),
                'currency' => $item->getCurrency(),
                'sum' => (float)$item->getFinalPrice(),
            ];
        }

        return [
            'items' => $items,
            'count' => count($items),
            'total' => array_sum(array_column($items, 'sum')),
        ];
    }

    private function applyPrice($item, int $productId): void
    {
        $price = (new PriceService())->getPriceForUser($productId);

        if ($price) {
            $item->setFields([
                'PRICE' => $price['PRICE'],
                'CURRENCY' => $price['CURRENCY'],
            ]);
        }
    }

    private function save(Basket $basket): void
    {
        $result = $basket->save();

        if (!$result->isSuccess()) {
            throw new \RuntimeException(implode('; ', $result->getErrorMessages()));
        }
    }
}
