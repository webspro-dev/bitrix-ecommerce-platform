<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('commerce.platform', [
    'Commerce\\Platform\\Config' => 'lib/Config.php',
    'Commerce\\Platform\\Logger' => 'lib/Logger.php',
    'Commerce\\Platform\\Pricing\\PriceService' => 'lib/Pricing/PriceService.php',
    'Commerce\\Platform\\Catalog\\CatalogService' => 'lib/Catalog/CatalogService.php',
    'Commerce\\Platform\\Basket\\BasketService' => 'lib/Basket/BasketService.php',
    'Commerce\\Platform\\Order\\OrderService' => 'lib/Order/OrderService.php',
    'Commerce\\Platform\\Favorite\\FavoriteService' => 'lib/Favorite/FavoriteService.php',
    'Commerce\\Platform\\Import\\CsvImportService' => 'lib/Import/CsvImportService.php',
]);
