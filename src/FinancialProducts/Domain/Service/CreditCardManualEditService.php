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
        ?Money $cost = null,
        ?string $logoUrl = null,
        ?string $deepLink = null
    ): void {
        $manualEdit = $this->manualEditRepository->findByCreditCard($creditCard);
        
        if (!$manualEdit) {
            $manualEdit = new CreditCardManualEdit($creditCard);
            $manualEdit->setCost($creditCard->getCost());
            $manualEdit->setIncentiveAmount($creditCard->getIncentiveAmount());
            $manualEdit->setLogoUrl($creditCard->getLogoUrl());
            $manualEdit->setDeepLink($creditCard->getDeepLink());
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

        if ($logoUrl !== null) {
            $manualEdit->setLogoUrl($logoUrl);
        }

        if ($deepLink !== null) {
            $manualEdit->setDeepLink($deepLink);
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
            'logoUrl' => $creditCard->getLogoUrl(),
            'deepLink' => $creditCard->getDeepLink(),
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
            if ($manualEdit->getLogoUrl() !== null) {
                $data['logoUrl'] = $manualEdit->getLogoUrl();
            }
            if ($manualEdit->getDeepLink() !== null) {
                $data['deepLink'] = $manualEdit->getDeepLink();
            }
        }
        
        return $data;
    }

    /**
     * Updates an existing manual edit or creates a new one based on the data of a credit card.
     * Only updates the fields that are empty (null) in the existing manual edit.
     */
    public function updateFromCreditCard(CreditCard $creditCard): void
    {
        $manualEdit = $this->getManualEdit($creditCard);
        
        if (!$manualEdit) {
            // If it doesn't exist, create a new one with the data of the card
            $manualEdit = new CreditCardManualEdit($creditCard);
            $manualEdit->setTitle($creditCard->getTitle());
            $manualEdit->setDescription($creditCard->getDescription());
            $manualEdit->setIncentiveAmount($creditCard->getIncentiveAmount());
            $manualEdit->setCost($creditCard->getCost());
            $manualEdit->setLogoUrl($creditCard->getLogoUrl());
            $manualEdit->setDeepLink($creditCard->getDeepLink());
            $this->manualEditRepository->save($manualEdit);
            return;
        }

        // If it exists, update only the fields that are empty
        $hasUpdates = false;
        
        if ($manualEdit->getTitle() === null || $manualEdit->getTitle() === '') {
            $manualEdit->setTitle($creditCard->getTitle());
            $hasUpdates = true;
        }
        
        if ($manualEdit->getDescription() === null || $manualEdit->getDescription() === '') {
            $manualEdit->setDescription($creditCard->getDescription());
            $hasUpdates = true;
        }
        
        if ($manualEdit->getIncentiveAmount() === null) {
            $manualEdit->setIncentiveAmount($creditCard->getIncentiveAmount());
            $hasUpdates = true;
        }
        
        if ($manualEdit->getCost() === null) {
            $manualEdit->setCost($creditCard->getCost());
            $hasUpdates = true;
        }
        
        if ($manualEdit->getLogoUrl() === null || $manualEdit->getLogoUrl() === '') {
            $manualEdit->setLogoUrl($creditCard->getLogoUrl());
            $hasUpdates = true;
        }
        
        if ($manualEdit->getDeepLink() === null || $manualEdit->getDeepLink() === '') {
            $manualEdit->setDeepLink($creditCard->getDeepLink());
            $hasUpdates = true;
        }
        
        // If there are fields to update, save the changes
        if ($hasUpdates) {
            $this->manualEditRepository->save($manualEdit);
        }
    }
} 