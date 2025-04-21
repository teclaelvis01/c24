<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\Cost;
use App\FinancialProducts\Domain\ValueObject\Money;
use Codeception\Test\Unit;

class CostTest extends Unit
{
    public function testCanCreateValidCost(): void
    {
        $money = new Money(100.50);
        $cost = new Cost($money);
        $this->assertEquals($money, $cost->getMoney());
    }

    public function testToStringReturnsMoneyString(): void
    {
        $money = new Money(100.50);
        $cost = new Cost($money);
        $this->assertEquals('100.50 EUR', (string) $cost);
    }
} 