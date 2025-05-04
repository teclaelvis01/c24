<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\Mapper;

use App\FinancialProducts\Application\DTO\CreditCardEditResponseDTO;
use App\FinancialProducts\Application\DTO\MoneyDTO;
use App\FinancialProducts\Application\DTO\BankDTO;
use App\FinancialProducts\Domain\Entity\CreditCardManualEdit;

class CreditCardEditResponseMapper
{
    public function toDTO(CreditCardManualEdit $creditCardManualEdit): CreditCardEditResponseDTO
    {
        return new CreditCardEditResponseDTO(
            id: $creditCardManualEdit->getId(),
            title: $creditCardManualEdit->getTitle(),
            description: $creditCardManualEdit->getDescription(),
            logoUrl: $creditCardManualEdit->getLogoUrl(),
            deepLink: $creditCardManualEdit->getDeepLink(),
            incentiveAmount: new MoneyDTO(
                amount: $creditCardManualEdit->getIncentiveAmount()->getAmount(),
                currency: $creditCardManualEdit->getIncentiveAmount()->getCurrency()->getName()
            ),
            cost: new MoneyDTO(
                amount: $creditCardManualEdit->getCost()->getAmount(),
                currency: $creditCardManualEdit->getCost()->getCurrency()->getName()
            ),
            bank: new BankDTO(
                id: $creditCardManualEdit->getCreditCard()->getBank()->getId(),
                name: $creditCardManualEdit->getCreditCard()->getBank()->getName()
            )
        );
    }
} 