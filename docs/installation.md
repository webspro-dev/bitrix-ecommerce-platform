# Installation

1. Install Bitrix core.
2. Copy the repository into the site root.
3. Configure `/local/modules/commerce.platform/lib/Config.php`.
4. Include the module:

```php
\Bitrix\Main\Loader::includeModule('commerce.platform');
```

5. Verify the product and SKU iblock IDs.
6. Configure catalog price types.
7. Configure user groups.
8. Create the Favorites HL-block.
9. Create the catalog page and include `commerce:catalog`.
10. Configure URL rewrite rules.

The repository intentionally does not include the proprietary Bitrix core or database dump.
