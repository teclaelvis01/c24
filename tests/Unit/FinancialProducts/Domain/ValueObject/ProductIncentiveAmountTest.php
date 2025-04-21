<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\Money;
use App\FinancialProducts\Domain\ValueObject\ProductIncentiveAmount;
use Codeception\Test\Unit;

class ProductIncentiveAmountTest extends Unit
{
    public function testCanCreateValidProductIncentiveAmount(): void
    {
        $money = new Money(100.50);
        $incentiveAmount = new ProductIncentiveAmount($money);
        $this->assertEquals($money, $incentiveAmount->getMoney());
    }

    public function testToStringReturnsMoneyString(): void
    {
        $money = new Money(100.50);
        $incentiveAmount = new ProductIncentiveAmount($money);
        $this->assertEquals('100.50 EUR', (string) $incentiveAmount);
    }
} 