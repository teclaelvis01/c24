<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\Service;

use App\FinancialProducts\Domain\Interfaces\CreditCardDataProviderInterface;
use App\FinancialProducts\Domain\Repository\CreditCardRepositoryInterface;
use App\FinancialProducts\Domain\Service\CreditCardManualEditService;
use App\FinancialProducts\Domain\Service\BankService;
use App\FinancialProducts\Domain\Entity\Bank;
use App\FinancialProducts\Domain\ValueObject\BankId;
use App\FinancialProducts\Domain\ValueObject\BankName;
use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Domain\ValueObject\ProductId;
use App\FinancialProducts\Domain\ValueObject\ProductTitle;
use App\FinancialProducts\Domain\ValueObject\CardType;
use App\FinancialProducts\Domain\ValueObject\ProductExtraInfo;
use App\FinancialProducts\Domain\ValueObject\ProductLogoUrl;
use App\FinancialProducts\Domain\ValueObject\ProductDeepLink;
use App\FinancialProducts\Domain\ValueObject\ProductFirstYearFee;
use App\FinancialProducts\Domain\ValueObject\ProductIncentiveAmount;
use App\FinancialProducts\Domain\ValueObject\Cost;

class CreditCardImportService
{
    public function __construct(
        private readonly CreditCardRepositoryInterface $creditCardRepository,
        private readonly CreditCardDataProviderInterface $creditCardDataProvider,
        private readonly CreditCardManualEditService $manualEditService,
        private readonly BankService $bankService
    ) {}

    public function importCreditCards(): void
    {
        $creditCardsData = $this->creditCardDataProvider->fetchCreditCards();

        foreach ($creditCardsData as $creditCard) {
            try {
                $existingCard = $this->creditCardRepository->findByExternalProductId($creditCard->getExternalProductId());

                // Obtener o crear el banco
                $bank = $this->bankService->findOrCreateBank(
                    new BankId($creditCard->getBank()->getExternalBankId()),
                    new BankName($creditCard->getBank()->getName())
                );

                if (!$existingCard) {
                    // Crear una nueva tarjeta con el banco
                    $newCard = new CreditCard(
                        new ProductId((int)$creditCard->getExternalProductId()),
                        new ProductTitle($creditCard->getTitle()),
                        new CardType($creditCard->getType()->getValue()),
                        new ProductExtraInfo($creditCard->getDescription()),
                        new ProductLogoUrl($creditCard->getLogoUrl()),
                        new ProductDeepLink($creditCard->getDeepLink()),
                        new ProductFirstYearFee($creditCard->getFirstYearFee()),
                        new ProductIncentiveAmount($creditCard->getIncentiveAmount()),
                        new Cost($creditCard->getCost()),
                        $bank
                    );
                    $this->creditCardRepository->save($newCard);
                    continue;
                }

                // Actualizar la tarjeta existente
                $existingCard->setBank($bank);
                
                $manualEdits = $this->manualEditService->getCombinedData($existingCard);
                
                $existingCard->setTitle($manualEdits['title'] ?? $creditCard->getTitle());
                $existingCard->setDescription($manualEdits['description'] ?? $creditCard->getDescription());
                $existingCard->setIncentiveAmount($manualEdits['incentiveAmount'] ?? $creditCard->getIncentiveAmount());
                $existingCard->setCost($manualEdits['cost'] ?? $creditCard->getCost());

                $this->creditCardRepository->save($existingCard);
            } catch (\Exception $e) {
                error_log('Error importando tarjeta de crédito: ' . $e->getMessage());
                continue;
            }
        }
    }
} 