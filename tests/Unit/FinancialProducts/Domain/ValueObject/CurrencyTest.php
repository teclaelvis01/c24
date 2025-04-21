<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\Currency;
use Codeception\Test\Unit;

class CurrencyTest extends Unit
{
    public function testCanCreateValidCurrency(): void
    {
        $currency = new Currency('EUR');
        $this->assertEquals('EUR', $currency->getCode());
        $this->assertEquals('€', $currency->getName());
    }

    public function testCanCreateDefaultCurrency(): void
    {
        $currency = new Currency();
        $this->assertEquals('EUR', $currency->getCode());
        $this->assertEquals('€', $currency->getName());
    }

    public function testCannotCreateInvalidCurrency(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid currency code. Allowed currencies are: EUR');
        new Currency('USD');
    }

    public function testStaticEURMethod(): void
    {
        $currency = Currency::EUR();
        $this->assertEquals('EUR', $currency->getCode());
        $this->assertEquals('€', $currency->getName());
    }

    public function testToStringReturnsCurrencyCode(): void
    {
        $currency = new Currency('EUR');
        $this->assertEquals('EUR', (string) $currency);
    }
} 