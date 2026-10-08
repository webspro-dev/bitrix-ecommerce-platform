<?php

namespace Commerce\Platform\Catalog;

use Commerce\Platform\Config;
use Commerce\Platform\Pricing\PriceService;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;

final class CatalogService
{
    public function getProducts(array $params = []): array
    {
        if (!Loader::includeModule('iblock')) {
            throw new \RuntimeException('Iblock module is unavailable.');
        }

        $page = max(1, (int)($params['page'] ?? 1));
        $limit = min(Config::MAX_PAGE_SIZE, max(1, (int)($params['limit'] ?? Config::DEFAULT_PAGE_SIZE)));
        $q = trim((string)($params['q'] ?? ''));
        $brand = trim((string)($params['brand'] ?? ''));
        $sort = (string)($params['sort'] ?? 'name');

        $filter = [
            '=IBLOCK_ID' => Config::PRODUCT_IBLOCK_ID,
            '=ACTIVE' => 'Y',
        ];

        if ($q !== '') {
            $filter['%NAME'] = $q;
        }

        if ($brand !== '') {
            $filter['=PROPERTY_BRAND'] = $brand;
        }

        $order = match ($sort) {
            'price_asc' => ['CATALOG_PRICE_1' => 'ASC'],
            'price_desc' => ['CATALOG_PRICE_1' => 'DESC'],
            default => ['NAME' => 'ASC'],
        };

        $cache = \Bitrix\Main\Data\Cache::createInstance();
        $cacheId = 'catalog:' . md5(serialize([$filter, $order, $page, $limit]));
        $cacheDir = '/commerce/catalog';

        if ($cache->initCache(300, $cacheId, $cacheDir)) {
            return $cache->getVars();
        }

        $rows = [];
        $query = ElementTable::getList([
            'select' => ['ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL'],
            'filter' => $filter,
            'order' => $order,
            'offset' => ($page - 1) * $limit,
            'limit' => $limit,
        ]);

        $priceService = new PriceService();

        while ($row = $query->fetch()) {
            $price = $priceService->getPrice((int)$row['ID']);

            $rows[] = [
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'code' => $row['CODE'],
                'url' => $row['DETAIL_PAGE_URL'],
                'price' => $price ? (float)$price['PRICE'] : null,
                'currency' => $price['CURRENCY'] ?? 'RUB',
            ];
        }

        $result = [
            'items' => $rows,
            'page' => $page,
            'limit' => $limit,
        ];

        $cache->startDataCache();
        $cache->endDataCache($result);

        return $result;
    }
}
