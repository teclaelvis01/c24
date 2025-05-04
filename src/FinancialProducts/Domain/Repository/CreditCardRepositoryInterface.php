<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Repository;

use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Domain\Entity\Bank;
use App\FinancialProducts\Domain\Entity\CreditCardManualEdit;

interface CreditCardRepositoryInterface
{
    public function findById(int $id): ?CreditCard;
    
    public function findByExternalProductId(string $externalProductId): ?CreditCard;
    
    public function save(CreditCard $creditCard): void;
    
    public function saveAll(array $creditCards): void;
    
    public function findByBank(Bank $bank): array;
    
    public function findAll(): array;
    

    /**
     * Find the latest manual edit for a credit card
     *
     * @param CreditCard $creditCard
     * @return CreditCardManualEdit|null
     */
    public function findLatestManualEdit(CreditCard $creditCard): ?CreditCardManualEdit;
} 