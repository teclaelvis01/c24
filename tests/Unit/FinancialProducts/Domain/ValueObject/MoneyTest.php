<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\Currency;
use App\FinancialProducts\Domain\ValueObject\Money;
use Codeception\Test\Unit;

class MoneyTest extends Unit
{
    public function testCanCreateValidMoney(): void
    {
        $money = new Money(100.50);
        $this->assertEquals(100.50, $money->getAmount());
        $this->assertEquals('EUR', $money->getCurrency()->getCode());
    }

    public function testCanCreateMoneyWithCustomCurrency(): void
    {
        $currency = new Currency('EUR');
        $money = new Money(100.50, $currency);
        $this->assertEquals(100.50, $money->getAmount());
        $this->assertEquals('EUR', $money->getCurrency()->getCode());
    }

    public function testCannotCreateMoneyWithNegativeAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount cannot be negative');
        new Money(-100.50);
    }

    public function testToStringReturnsFormattedString(): void
    {
        $money = new Money(100.50);
        $this->assertEquals('100.50 EUR', (string) $money);
    }

    public function testCanCreateMoneyFromStringWithComma(): void
    {
        $money = Money::fromString('100,50');
        $this->assertEquals(100.50, $money->getAmount());
        $this->assertEquals('EUR', $money->getCurrency()->getCode());
    }

    public function testCanCreateMoneyFromStringWithDot(): void
    {
        $money = Money::fromString('100.50');
        $this->assertEquals(100.50, $money->getAmount());
        $this->assertEquals('EUR', $money->getCurrency()->getCode());
    }

    public function testCannotCreateMoneyFromInvalidString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid money format');
        Money::fromString('invalid');
    }

    public function testCanCreateMoneyFromStringWithCustomCurrency(): void
    {
        $currency = new Currency('EUR');
        $money = Money::fromString('100,50', $currency);
        $this->assertEquals(100.50, $money->getAmount());
        $this->assertEquals('EUR', $money->getCurrency()->getCode());
    }
} 