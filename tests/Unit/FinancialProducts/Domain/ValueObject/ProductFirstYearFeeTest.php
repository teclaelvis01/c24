<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\Money;
use App\FinancialProducts\Domain\ValueObject\ProductFirstYearFee;
use Codeception\Test\Unit;

class ProductFirstYearFeeTest extends Unit
{
    public function testCanCreateValidProductFirstYearFee(): void
    {
        $money = new Money(100.50);
        $firstYearFee = new ProductFirstYearFee($money);
        $this->assertEquals($money, $firstYearFee->getMoney());
    }

    public function testToStringReturnsMoneyString(): void
    {
        $money = new Money(100.50);
        $firstYearFee = new ProductFirstYearFee($money);
        $this->assertEquals('100.50 EUR', (string) $firstYearFee);
    }
} 