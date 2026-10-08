<?php

namespace Commerce\Platform\Pricing;

use Commerce\Platform\Config;
use Bitrix\Catalog\PriceTable;
use Bitrix\Main\Loader;

final class PriceService
{
    public function getPriceTypeForUser(int $userId = 0): int
    {
        global $USER;

        if ($userId <= 0 && is_object($USER)) {
            $userId = (int)$USER->GetID();
        }

        if ($userId <= 0) {
            return Config::PRICE_RETAIL;
        }

        $groups = \CUser::GetUserGroup($userId);

        if (in_array(Config::VIP_GROUP_ID, $groups, true)) {
            return Config::PRICE_VIP;
        }

        if (in_array(Config::WHOLESALE_GROUP_ID, $groups, true)) {
            return Config::PRICE_WHOLESALE;
        }

        return Config::PRICE_RETAIL;
    }

    public function getPrice(int $productId, int $userId = 0): ?array
    {
        if (!Loader::includeModule('catalog')) {
            throw new \RuntimeException('Catalog module is unavailable.');
        }

        $typeId = $this->getPriceTypeForUser($userId);

        $row = PriceTable::getList([
            'select' => ['ID', 'PRODUCT_ID', 'CATALOG_GROUP_ID', 'PRICE', 'CURRENCY'],
            'filter' => [
                '=PRODUCT_ID' => $productId,
                '=CATALOG_GROUP_ID' => $typeId,
            ],
            'limit' => 1,
        ])->fetch();

        return $row ?: null;
    }

    public function getPriceForUser(int $productId, int $userId = 0): ?array
    {
        return $this->getPrice($productId, $userId);
    }
}
