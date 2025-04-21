<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\Service;

use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Domain\Repository\CreditCardRepositoryInterface;
use App\FinancialProducts\Domain\Service\CreditCardManualEditService;
use App\FinancialProducts\Domain\ValueObject\Money;
use App\FinancialProducts\Domain\ValueObject\Currency;

class CreditCardUpdateService
{
    public function __construct(
        private readonly CreditCardRepositoryInterface $creditCardRepository,
        private readonly CreditCardManualEditService $manualEditService
    ) {}

    public function updateCard(
        int $id,
        ?string $title = null,
        ?string $description = null,
        ?array $incentiveAmount = null,
        ?array $cost = null
    ): void {
        $creditCard = $this->creditCardRepository->findById($id);
        
        if (!$creditCard) {
            throw new \InvalidArgumentException('Credit card not found');
        }

        $incentiveMoney = null;
        if ($incentiveAmount !== null) {
            $incentiveMoney = new Money(
                $incentiveAmount['amount'],
                new Currency($incentiveAmount['currencyCode'])
            );
        }

        $costMoney = null;
        if ($cost !== null) {
            $costMoney = new Money(
                $cost['amount'],
                new Currency($cost['currencyCode'])
            );
        }

        $this->manualEditService->editCreditCard(
            $creditCard,
            $title,
            $description,
            $incentiveMoney,
            $costMoney
        );
    }
} 