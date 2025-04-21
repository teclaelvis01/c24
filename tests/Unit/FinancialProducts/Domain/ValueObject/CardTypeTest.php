<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\CardType;
use Codeception\Test\Unit;

class CardTypeTest extends Unit
{
    public function testCanCreateValidCreditCardType(): void
    {
        $cardType = new CardType('credit');
        $this->assertEquals('credit', $cardType->getValue());
        $this->assertTrue($cardType->isCredit());
        $this->assertFalse($cardType->isDebit());
    }

    public function testCanCreateValidDebitCardType(): void
    {
        $cardType = new CardType('debit');
        $this->assertEquals('debit', $cardType->getValue());
        $this->assertTrue($cardType->isDebit());
        $this->assertFalse($cardType->isCredit());
    }

    public function testCannotCreateInvalidCardType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid card type. Must be one of: credit, debit');
        new CardType('invalid');
    }

    public function testToStringReturnsCardType(): void
    {
        $cardType = new CardType('credit');
        $this->assertEquals('credit', (string) $cardType);
    }

    public function testCaseInsensitiveCardTypeCreation(): void
    {
        $cardType = new CardType('CREDIT');
        $this->assertEquals('credit', $cardType->getValue());
    }
} 