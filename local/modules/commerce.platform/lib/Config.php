<?php

namespace Commerce\Platform;

final class Config
{
    public const PRODUCT_IBLOCK_ID = 10;
    public const SKU_IBLOCK_ID = 11;

    public const PRICE_RETAIL = 1;
    public const PRICE_WHOLESALE = 2;
    public const PRICE_VIP = 3;

    public const WHOLESALE_GROUP_ID = 7;
    public const VIP_GROUP_ID = 8;

    public const FAVORITE_HL_ID = 1;
    public const LOG_FILE = '/local/logs/commerce-platform.log';

    public const DEFAULT_PAGE_SIZE = 24;
    public const MAX_PAGE_SIZE = 100;
}
