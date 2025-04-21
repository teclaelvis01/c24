<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Repository;

use App\FinancialProducts\Domain\Entity\CreditCardManualEdit;
use App\FinancialProducts\Domain\Entity\CreditCard;

interface CreditCardManualEditRepositoryInterface
{
    public function findByCreditCard(CreditCard $creditCard): ?CreditCardManualEdit;
    
    public function save(CreditCardManualEdit $manualEdit): void;
} 