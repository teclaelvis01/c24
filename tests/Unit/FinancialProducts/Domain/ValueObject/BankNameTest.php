<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\BankName;
use Codeception\Test\Unit;

class BankNameTest extends Unit
{
    public function testCanCreateValidBankName(): void
    {
        $bankName = new BankName('Test Bank');
        $this->assertEquals('Test Bank', $bankName->getValue());
    }

    public function testCannotCreateBankNameWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Bank name cannot be empty');
        new BankName('');
    }

    public function testCannotCreateBankNameWithTooLongString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Bank name cannot be longer than 500 characters');
        new BankName(str_repeat('a', 501));
    }
} 