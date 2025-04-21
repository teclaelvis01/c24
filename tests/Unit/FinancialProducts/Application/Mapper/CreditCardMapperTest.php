<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Application\Mapper;

use App\FinancialProducts\Application\Mapper\CreditCardMapper;
use App\FinancialProducts\Domain\Entity\Bank;
use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Domain\ValueObject\BankId;
use App\FinancialProducts\Domain\ValueObject\BankName;
use App\FinancialProducts\Domain\ValueObject\CardType;
use App\FinancialProducts\Domain\ValueObject\Cost;
use App\FinancialProducts\Domain\ValueObject\Money;
use App\FinancialProducts\Domain\ValueObject\ProductDeepLink;
use App\FinancialProducts\Domain\ValueObject\ProductExtraInfo;
use App\FinancialProducts\Domain\ValueObject\ProductFirstYearFee;
use App\FinancialProducts\Domain\ValueObject\ProductId;
use App\FinancialProducts\Domain\ValueObject\ProductIncentiveAmount;
use App\FinancialProducts\Domain\ValueObject\ProductLogoUrl;
use App\FinancialProducts\Domain\ValueObject\ProductTitle;
use Codeception\Test\Unit;
use SimpleXMLElement;

class CreditCardMapperTest extends Unit
{
    private CreditCardMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new CreditCardMapper();
    }

    public function testCanMapXmlToCreditCard(): void
    {
        $xml = new SimpleXMLElement(<<<XML
            <product>
                <productid>123</productid>
                <bankid>456</bankid>
                <bank>Test Bank</bank>
                <produkt>Test Card</produkt>
                <cardtype_text>credit</cardtype_text>
                <anmerkungen>Test description</anmerkungen>
                <logo>https://example.com/logo.png</logo>
                <link>https://example.com/product/123</link>
                <gebuehrenjahr1>100,50</gebuehrenjahr1>
                <incentive_amount>50,00</incentive_amount>
                <kosten>10,00</kosten>
            </product>
        XML);

        $creditCard = $this->mapper->fromXml($xml);

        $this->assertInstanceOf(CreditCard::class, $creditCard);
        $this->assertInstanceOf(Bank::class, $creditCard->getBank());
        
        $this->assertEquals('123', $creditCard->getExternalProductId());
        $this->assertEquals('Test Card', $creditCard->getTitle());
        $this->assertEquals('credit', $creditCard->getType()->getValue());
        $this->assertEquals('Test description', $creditCard->getDescription());
        $this->assertEquals('https://example.com/logo.png', $creditCard->getLogoUrl());
        $this->assertEquals('https://example.com/product/123', $creditCard->getDeepLink());
        $this->assertEquals('100.50', $creditCard->getFirstYearFee()->getAmount());
        $this->assertEquals('50.00', $creditCard->getIncentiveAmount()->getAmount());
        $this->assertEquals('10.00', $creditCard->getCost()->getAmount());
        
        $bank = $creditCard->getBank();
        $this->assertEquals(456, $bank->getExternalBankId());
        $this->assertEquals('Test Bank', $bank->getName());
    }

    public function testThrowsExceptionWithInvalidCardType(): void
    {
        $xml = new SimpleXMLElement(<<<XML
            <product>
                <productid>123</productid>
                <bankid>456</bankid>
                <bank>Test Bank</bank>
                <produkt>Test Card</produkt>
                <cardtype_text>invalid_type</cardtype_text>
                <anmerkungen>Test description</anmerkungen>
                <logo>https://example.com/logo.png</logo>
                <link>https://example.com/product/123</link>
                <gebuehrenjahr1>100,50</gebuehrenjahr1>
                <incentive_amount>50,00</incentive_amount>
                <kosten>10,00</kosten>
            </product>
        XML);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid card type. Must be one of: credit, debit');

        $this->mapper->fromXml($xml);
    }

    public function testThrowsExceptionWithInvalidMoneyFormat(): void
    {
        $xml = new SimpleXMLElement(<<<XML
            <product>
                <productid>123</productid>
                <bankid>456</bankid>
                <bank>Test Bank</bank>
                <produkt>Test Card</produkt>
                <cardtype_text>credit</cardtype_text>
                <anmerkungen>Test description</anmerkungen>
                <logo>https://example.com/logo.png</logo>
                <link>https://example.com/product/123</link>
                <gebuehrenjahr1>invalid</gebuehrenjahr1>
                <incentive_amount>50,00</incentive_amount>
                <kosten>10,00</kosten>
            </product>
        XML);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid money format');

        $this->mapper->fromXml($xml);
    }
} 