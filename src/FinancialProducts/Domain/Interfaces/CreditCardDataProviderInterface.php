<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Interfaces;
use App\FinancialProducts\Domain\Entity\CreditCard;

interface CreditCardDataProviderInterface
{
    /**
     * @return array<CreditCard>
     */
    public function fetchCreditCards(): array;
}