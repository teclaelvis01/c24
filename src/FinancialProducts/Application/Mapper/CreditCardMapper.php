<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\Mapper;

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
use SimpleXMLElement;

class CreditCardMapper implements CreditCardMapperInterface
{
    public function fromXml(SimpleXMLElement $xml): CreditCard
    {
        $bank = new Bank(
            new BankId((int) $xml->bankid),
            new BankName((string) $xml->bank)
        );
        
        $creditCard = new CreditCard(
            new ProductId((int) $xml->productid),
            new ProductTitle((string) $xml->produkt),
            new CardType((string) $xml->cardtype_text),
            new ProductExtraInfo(html_entity_decode((string) $xml->anmerkungen)),
            new ProductLogoUrl((string) $xml->logo),
            new ProductDeepLink((string) $xml->link),
            new ProductFirstYearFee(Money::fromString((string) $xml->gebuehrenjahr1)),
            new ProductIncentiveAmount(Money::fromString((string) $xml->incentive_amount)),
            new Cost(Money::fromString((string) $xml->kosten)),
            $bank
        );

        return $creditCard;
    }
} 