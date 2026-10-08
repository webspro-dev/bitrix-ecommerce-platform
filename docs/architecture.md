# Architecture

## Domain services

`CatalogService` — catalog selection, filtering and cache.

`PriceService` — determines effective price type from user groups.

`BasketService` — basket mutations and recalculation.

`OrderService` — validates and creates Bitrix Sale orders.

`FavoriteService` — HL-block persistence for favorites.

`CsvImportService` — validated upsert import.

## Important business rule

No controller, template or JavaScript code may calculate the final product price.

Only `PriceService` is authoritative.

The same service is called:

- in catalog;
- in basket;
- before order creation.

This prevents price manipulation through browser requests.

## AJAX

Mutating endpoints require Bitrix session validation:

```php
check_bitrix_sessid()
```

## Cache

Catalog responses are cached with Bitrix Data Cache. Cache keys include filters, sorting and pagination.
