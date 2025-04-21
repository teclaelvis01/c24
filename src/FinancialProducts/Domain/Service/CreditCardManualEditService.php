<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Service;

use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Domain\Entity\CreditCardManualEdit;
use App\FinancialProducts\Domain\Repository\CreditCardManualEditRepositoryInterface;
use App\FinancialProducts\Domain\ValueObject\Money;

class CreditCardManualEditService
{
    public function __construct(
        private readonly CreditCardManualEditRepositoryInterface $manualEditRepository
    ) {}

    public function editCreditCard(
        CreditCard $creditCard,
        ?string $title = null,
        ?string $description = null,
        ?Money $incentiveAmount = null,
        ?Money $cost = null
    ): void {
        $manualEdit = $this->manualEditRepository->findByCreditCard($creditCard);
        
        if (!$manualEdit) {
            $manualEdit = new CreditCardManualEdit($creditCard);
            $manualEdit->setCost($creditCard->getCost());
            $manualEdit->setIncentiveAmount($creditCard->getIncentiveAmount());
        }
        
        if ($title !== null) {
            $manualEdit->setTitle($title);
        }
        
        if ($description !== null) {
            $manualEdit->setDescription($description);
        }
        
        if ($incentiveAmount !== null) {
            $manualEdit->setIncentiveAmount($incentiveAmount);
        }
        
        if ($cost !== null) {
            $manualEdit->setCost($cost);
        }
        
        $this->manualEditRepository->save($manualEdit);
    }

    public function getManualEdit(CreditCard $creditCard): ?CreditCardManualEdit
    {
        return $this->manualEditRepository->findByCreditCard($creditCard);
    }

    public function getCombinedData(CreditCard $creditCard): array
    {
        $manualEdit = $this->getManualEdit($creditCard);
        
        $data = [
            'title' => $creditCard->getTitle(),
            'description' => $creditCard->getDescription(),
            'incentiveAmount' => $creditCard->getIncentiveAmount(),
            'cost' => $creditCard->getCost(),
        ];
        
        if ($manualEdit) {
            if ($manualEdit->getTitle() !== null) {
                $data['title'] = $manualEdit->getTitle();
            }
            if ($manualEdit->getDescription() !== null) {
                $data['description'] = $manualEdit->getDescription();
            }
            if ($manualEdit->getIncentiveAmount() !== null) {
                $data['incentiveAmount'] = $manualEdit->getIncentiveAmount();
            }
            if ($manualEdit->getCost() !== null) {
                $data['cost'] = $manualEdit->getCost();
            }
        }
        
        return $data;
    }
} 