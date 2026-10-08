<?php

namespace Commerce\Platform\Favorite;

use Commerce\Platform\Config;
use Bitrix\Main\Loader;

final class FavoriteService
{
    public function toggle(int $productId): bool
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            throw new \RuntimeException('Authorization required.');
        }

        if (!Loader::includeModule('highloadblock')) {
            throw new \RuntimeException('Highloadblock module is unavailable.');
        }

        $hl = \Bitrix\Highloadblock\HighloadBlockTable::getById(Config::FAVORITE_HL_ID)->fetch();

        if (!$hl) {
            throw new \RuntimeException('Favorites HL-block not configured.');
        }

        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hl);
        $dataClass = $entity->getDataClass();

        $existing = $dataClass::getList([
            'select' => ['ID'],
            'filter' => [
                '=UF_USER_ID' => (int)$USER->GetID(),
                '=UF_PRODUCT_ID' => $productId,
            ],
            'limit' => 1,
        ])->fetch();

        if ($existing) {
            $dataClass::delete((int)$existing['ID']);
            return false;
        }

        $dataClass::add([
            'UF_USER_ID' => (int)$USER->GetID(),
            'UF_PRODUCT_ID' => $productId,
        ]);

        return true;
    }

    public function all(): array
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            return [];
        }

        if (!Loader::includeModule('highloadblock')) {
            return [];
        }

        $hl = \Bitrix\Highloadblock\HighloadBlockTable::getById(Config::FAVORITE_HL_ID)->fetch();

        if (!$hl) {
            return [];
        }

        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hl);
        $dataClass = $entity->getDataClass();

        $result = $dataClass::getList([
            'select' => ['UF_PRODUCT_ID'],
            'filter' => ['=UF_USER_ID' => (int)$USER->GetID()],
        ]);

        $ids = [];

        while ($row = $result->fetch()) {
            $ids[] = (int)$row['UF_PRODUCT_ID'];
        }

        return $ids;
    }
}
