<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\Mapper;

use App\FinancialProducts\Application\DTO\CreditCardResponseDTO;
use App\FinancialProducts\Application\DTO\MoneyDTO;
use App\FinancialProducts\Application\DTO\BankDTO;
use App\FinancialProducts\Domain\Entity\CreditCard;

class CreditCardResponseMapper
{
    public function toDTO(CreditCard $creditCard): CreditCardResponseDTO
    {
        return new CreditCardResponseDTO(
            id: $creditCard->getId(),
            externalProductId: $creditCard->getExternalProductId(),
            title: $creditCard->getTitle(),
            type: $creditCard->getType()->getValue(),
            description: $creditCard->getDescription(),
            logoUrl: $creditCard->getLogoUrl(),
            deepLink: $creditCard->getDeepLink(),
            firstYearFee: new MoneyDTO(
                amount: $creditCard->getFirstYearFee()->getAmount(),
                currency: $creditCard->getFirstYearFee()->getCurrency()->getName()
            ),
            incentiveAmount: new MoneyDTO(
                amount: $creditCard->getIncentiveAmount()->getAmount(),
                currency: $creditCard->getIncentiveAmount()->getCurrency()->getName()
            ),
            cost: new MoneyDTO(
                amount: $creditCard->getCost()->getAmount(),
                currency: $creditCard->getCost()->getCurrency()->getName()
            ),
            bank: new BankDTO(
                id: $creditCard->getBank()->getId(),
                name: $creditCard->getBank()->getName()
            )
        );
    }
} 