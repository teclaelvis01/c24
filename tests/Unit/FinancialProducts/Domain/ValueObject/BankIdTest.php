<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\BankId;
use Codeception\Test\Unit;

class BankIdTest extends Unit
{
    public function testCanCreateValidBankId(): void
    {
        $bankId = new BankId(1);
        $this->assertEquals(1, $bankId->getValue());
    }

    public function testCannotCreateBankIdWithZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Bank ID must be greater than 0');
        new BankId(0);
    }

    public function testCannotCreateBankIdWithNegativeNumber(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Bank ID must be greater than 0');
        new BankId(-1);
    }
} 