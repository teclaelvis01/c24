<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\Entity;

use App\FinancialProducts\Domain\Entity\Bank;
use App\FinancialProducts\Domain\ValueObject\BankId;
use App\FinancialProducts\Domain\ValueObject\BankName;
use App\FinancialProducts\Domain\Entity\CreditCard;
use Codeception\Test\Unit;

class BankTest extends Unit
{
    private Bank $bank;
    private BankId $bankId;
    private BankName $bankName;

    protected function _before(): void
    {
        $this->bankId = new BankId(123);
        $this->bankName = new BankName('Banco de Prueba');
        $this->bank = new Bank($this->bankId, $this->bankName);
    }

    public function testBankCreation(): void
    {
        $this->assertEquals(123, $this->bank->getExternalBankId());
        $this->assertEquals('Banco de Prueba', $this->bank->getName());
        $this->assertEmpty($this->bank->getCreditCards());
    }
} 