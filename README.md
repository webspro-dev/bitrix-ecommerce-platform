# Bitrix E-commerce Platform

Полноценный production-oriented интернет-магазин на 1С-Битрикс: Управление сайтом.

Проект построен вокруг D7, Sale, Catalog, IBlock ORM, AJAX и REST API. Архитектура разделяет HTTP-слой, бизнес-сервисы и работу с данными.

## Возможности

- каталог товаров и торговых предложений;
- ЧПУ URL каталога и товара;
- пагинация и сортировка;
- AJAX-фильтр по бренду, цене и характеристикам;
- сохранение фильтра в URL;
- серверное определение цены по группе пользователя;
- автоматический пересчёт корзины;
- купоны Bitrix Sale;
- оформление заказа;
- личный кабинет и история заказов;
- избранное через Highload-блок;
- REST API v1;
- CSV-импорт;
- журналирование ошибок;
- Bitrix Cache;
- CLI-импорт;
- CSRF-защита AJAX;
- GitHub Actions;
- PHPUnit foundation;
- Docker development environment.

## Архитектура

```text
HTTP / Bitrix Component / REST / CLI
                |
                v
        Application Services
                |
       +--------+--------+
       |        |        |
    Catalog   Basket   Order
       |        |        |
       +--------+--------+
                |
          Bitrix D7 / Sale
                |
              MySQL
```

Ключевой принцип: браузер никогда не является источником истины для цены.

```text
User
 -> User Groups
 -> PriceService
 -> Catalog Price
 -> Basket
 -> Order
```

## Установка

### Требования

- PHP 8.2+
- 1C-Bitrix: Управление сайтом
- MySQL 8 / MariaDB
- Composer
- Nginx или Apache

### Установка в существующий Bitrix

Скопировать проект в корень сайта.

Затем:

```bash
composer install
php local/scripts/install_project.php
```

После установки проверь конфигурацию:

```text
/local/modules/commerce.platform/lib/Config.php
```

В установщике можно создать:

- инфоблок товаров;
- инфоблок торговых предложений;
- типы цен;
- группы пользователей;
- HL-блок избранного.

## Каталог

Основная страница:

```text
/catalog/
```

Формат URL:

```text
/catalog/
catalog/brand/iphone/
catalog/product/iphone-15/
```

В production-проекте рекомендуется использовать стандартный Bitrix URL rewrite и SEO-шаблоны.

## Фильтр

Поддерживаются:

- строковый поиск;
- бренд;
- минимальная цена;
- максимальная цена;
- сортировка;
- пагинация.

Состояние хранится в query string:

```text
/catalog/?q=iphone&brand=Apple&min_price=50000&max_price=150000&sort=price_asc
```

AJAX обновляет каталог без полной перезагрузки.

## Ценообразование

Настроены три типа:

- Retail;
- Wholesale;
- VIP.

Приоритет:

```text
VIP > Wholesale > Retail
```

Тип цены определяется сервером через `PriceService`.

Корзина вызывает тот же сервис при пересчёте. Это исключает ситуацию, когда каталог показывает одну цену, а заказ получает другую.

## Корзина

Реализованы:

- добавление;
- удаление;
- изменение количества;
- пересчёт;
- применение купона;
- удаление купона;
- автоматический пересчёт цены.

AJAX endpoint:

```text
/local/ajax/basket.php
```

## Заказы

Используется Bitrix Sale API.

Поток:

```text
Basket
 -> Order
 -> Person type
 -> Payment
 -> Shipment
 -> Save
```

Цена повторно проверяется на сервере во время оформления заказа.

## Личный кабинет

```text
/personal/
```

Доступны:

- профиль;
- история заказов;
- просмотр состава заказа;
- избранное.

## Избранное

Избранное хранится в HL-блоке для авторизованных пользователей.

Это позволяет:

- хранить данные между устройствами;
- не зависеть от PHP session;
- использовать D7 ORM;
- расширять модель дополнительными полями.

## REST API

```http
GET /api/v1/products.php
GET /api/v1/products.php?id=123
```

Параметры:

```text
page
limit
q
brand
min_price
max_price
sort
```

Пример:

```text
/api/v1/products.php?page=1&limit=20&sort=price_asc
```

Формат:

```json
{
  "success": true,
  "data": {
    "items": [],
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 0
    }
  }
}
```

## CSV импорт

Файл:

```text
data/products.csv
```

Запуск:

```bash
php local/scripts/import_products.php data/products.csv
```

Поддерживается upsert по `external_id`.

Ошибочная строка не прерывает весь импорт.

## Логирование

Основной лог:

```text
/local/logs/commerce-platform.log
```

Логируются:

- ошибки API;
- ошибки импорта;
- ошибки корзины;
- ошибки заказа.

## Cache

Каталог использует Bitrix Cache.

После изменения товаров кэш рекомендуется очищать штатными средствами Bitrix или событиями инфоблока.

## Безопасность

- CSRF для AJAX;
- серверное ценообразование;
- валидация входных параметров;
- ограничение CSV CLI;
- проверка авторизации;
- проверка прав;
- безопасный JSON;
- отсутствие внутренних exception message в production API.

## Git workflow

Ветки:

```text
main
stage/catalog
stage/filter
stage/basket
stage/pricing
stage/order
stage/api
```

Финальный commit:

```text
feat: complete ecommerce platform
```

## Roadmap

Следующие production-улучшения:

- Redis;
- очередь импорта;
- Elasticsearch/OpenSearch;
- интеграция с 1С;
- платежные системы;
- доставки;
- PHPUnit integration tests;
- OpenAPI;
- CI/CD deployment;
