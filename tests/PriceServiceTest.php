<?php

namespace Commerce\Platform\Tests;

use Commerce\Platform\Pricing\PriceService;
use PHPUnit\Framework\TestCase;

final class PriceServiceTest extends TestCase
{
    public function testServiceCanBeCreated(): void
    {
        self::assertInstanceOf(PriceService::class, new PriceService());
    }
}
