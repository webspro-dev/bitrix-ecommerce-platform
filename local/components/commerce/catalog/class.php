<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Commerce\Platform\Catalog\CatalogService;

class CommerceCatalogComponent extends CBitrixComponent implements Controllerable
{
    public function configureActions(): array
    {
        return [];
    }

    public function executeComponent()
    {
        $service = new CatalogService();

        $this->arResult = $service->getProducts([
            'page' => (int)($_GET['page'] ?? 1),
            'limit' => (int)($this->arParams['PAGE_SIZE'] ?? 24),
            'q' => $_GET['q'] ?? '',
            'brand' => $_GET['brand'] ?? '',
            'sort' => $_GET['sort'] ?? 'name',
        ]);

        $this->includeComponentTemplate();
    }
}
